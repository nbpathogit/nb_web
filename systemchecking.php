<?php

require 'includes/init.php';
Auth::requireLogin();
require 'user_auth.php';

function systemcheckingFindCommand($command)
{
    $path = getenv('PATH');
    if ($path === false || $path === '') {
        return false;
    }

    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    $extensions = [''];
    if ($isWindows) {
        $pathExtensions = getenv('PATHEXT');
        $extensions = $pathExtensions
            ? array_map('strtolower', explode(';', $pathExtensions))
            : ['.com', '.exe', '.bat', '.cmd'];
    }

    foreach (explode(PATH_SEPARATOR, $path) as $directory) {
        $directory = trim($directory, " \t\n\r\0\x0B\"");
        if ($directory === '') {
            continue;
        }

        foreach ($extensions as $extension) {
            $candidate = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . $command . $extension;
            if (is_file($candidate) && ($isWindows || is_executable($candidate))) {
                return $candidate;
            }
        }
    }

    return false;
}

function systemcheckingIniValueMeetsExample($directive, $actualValue, $exampleValue, $isSize)
{
    if ($actualValue === false || !preg_match('/^\s*(-?\d+)\s*([KMG]?)\s*$/i', $actualValue, $matches)) {
        return false;
    }

    $actualNumber = (int) $matches[1];
    if (($directive === 'memory_limit' && $actualNumber === -1)
        || ($directive === 'max_input_time' && $actualNumber === -1)
        || ($directive === 'max_execution_time' && $actualNumber === 0)) {
        return true;
    }

    if ($actualNumber < 0) {
        return false;
    }

    if ($isSize) {
        $unitMultipliers = [
            '' => 1,
            'K' => 1024,
            'M' => 1024 * 1024,
            'G' => 1024 * 1024 * 1024,
        ];
        $actualBytes = $actualNumber * $unitMultipliers[strtoupper($matches[2])];
        $exampleMatches = [];
        if (!preg_match('/^(\d+)\s*([KMG]?)$/i', $exampleValue, $exampleMatches)) {
            return false;
        }
        $exampleBytes = (int) $exampleMatches[1] * $unitMultipliers[strtoupper($exampleMatches[2])];
        return $actualBytes >= $exampleBytes;
    }

    return $actualNumber >= (int) $exampleValue;
}

$isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
$currentOs = PHP_OS_FAMILY === 'Windows' ? 'Windows'
    : (PHP_OS_FAMILY === 'Linux' ? 'Linux' : PHP_OS_FAMILY);
$imageMagickPath = systemcheckingFindCommand('magick');
if ($imageMagickPath === false && !$isWindows) {
    $imageMagickPath = systemcheckingFindCommand('convert');
}

$commandChecks = [
    [
        'name' => 'Ghostscript',
        'path' => systemcheckingFindCommand($isWindows ? 'gswin64c' : 'gs'),
        'purpose' => 'Ghostscript PDF/PostScript interpreter',
    ],
    [
        'name' => '7z',
        'path' => systemcheckingFindCommand('7z'),
        'purpose' => '7-Zip command-line utility',
    ],
    [
        'name' => 'zip',
        'path' => systemcheckingFindCommand('zip'),
        'purpose' => 'ZIP archive utility',
    ],
    [
        'name' => 'mv',
        'path' => systemcheckingFindCommand('mv'),
        'purpose' => 'File move utility',
    ],
    [
        'name' => 'ImageMagick',
        'path' => $imageMagickPath,
        'purpose' => 'ImageMagick CLI (magick' . (!$isWindows ? ' or convert' : '') . ')',
    ],
];

$phpIniExamples = [
    ['name' => 'max_execution_time', 'example' => '120', 'isSize' => false],
    ['name' => 'max_input_time', 'example' => '60', 'isSize' => false],
    ['name' => 'memory_limit', 'example' => '512M', 'isSize' => true],
    ['name' => 'post_max_size', 'example' => '40M', 'isSize' => true],
    ['name' => 'upload_max_filesize', 'example' => '40M', 'isSize' => true],
];

foreach ($phpIniExamples as &$phpIniExample) {
    $phpIniExample['current'] = ini_get($phpIniExample['name']);
    $phpIniExample['meetsExample'] = systemcheckingIniValueMeetsExample(
        $phpIniExample['name'],
        $phpIniExample['current'],
        $phpIniExample['example'],
        $phpIniExample['isSize']
    );
}
unset($phpIniExample);

require 'includes/header.php';
?>

<?php if (!Auth::isLoggedIn()) : ?>
    <?php require 'blockopen.php'; ?>
    You are not logged in.
    <?php require 'blockclose.php'; ?>
<?php elseif (!$isCurUserAdmin) : ?>
    <?php require 'blockopen.php'; ?>
    You are not authorized to view system command details.
    <?php require 'blockclose.php'; ?>
<?php else : ?>
    <div class="container-fluid pt-4 px-4">
        <div class="row bg-nb bg-blue-a rounded align-items-center justify-content-center p-3 mx-1">
            <div class="col-12">
                <h3>System Command Check</h3>
                <p>Checks whether each command can be found in the PHP process PATH. No system commands are executed.</p>
                <p>Current OS: <strong><?= htmlspecialchars($currentOs, ENT_QUOTES, 'UTF-8') ?></strong>
                    (PHP_OS: <?= htmlspecialchars(PHP_OS, ENT_QUOTES, 'UTF-8') ?>)</p>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Command</th>
                                <th>Status</th>
                                <th>Location</th>
                                <th>Purpose</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandChecks as $check) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($check['name'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td>
                                        <?php if ($check['path'] !== false) : ?>
                                            <span class="text-success">Available</span>
                                        <?php else : ?>
                                            <span class="text-danger">Not found</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-dark">
                                        <?php if ($check['path'] !== false) : ?>
                                            <code class="text-dark"><?= htmlspecialchars($check['path'], ENT_QUOTES, 'UTF-8') ?></code>
                                        <?php else : ?>
                                            &mdash;
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($check['purpose'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <h4 class="mt-4">PHP Configuration Check</h4>
                <p>Current effective PHP settings compared with the requested example values. Values at or above the example pass.</p>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>php.ini directive</th>
                                <th>Status</th>
                                <th>Current value</th>
                                <th>Example minimum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($phpIniExamples as $setting) : ?>
                                <tr>
                                    <td><code><?= htmlspecialchars($setting['name'], ENT_QUOTES, 'UTF-8') ?></code></td>
                                    <td>
                                        <?php if ($setting['meetsExample']) : ?>
                                            <span class="text-success">Meets example</span>
                                        <?php elseif ($setting['current'] === false) : ?>
                                            <span class="text-danger">Unavailable</span>
                                        <?php else : ?>
                                            <span class="text-danger">Below example</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-dark">
                                        <?= $setting['current'] === false
                                            ? 'Unavailable'
                                            : htmlspecialchars($setting['current'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td><?= htmlspecialchars($setting['example'], ENT_QUOTES, 'UTF-8') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
