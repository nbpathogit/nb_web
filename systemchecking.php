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
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
