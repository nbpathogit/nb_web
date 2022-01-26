<?php $base = strtok($_SERVER["REQUEST_URI"], '?'); ?>

<?php if (0) : ?>  //disable
<nav>
    <ul>
        <li>
            <?php if ($paginator->previous) : ?>
                <a href="<?= $base; ?>?page=<?= $paginator->previous; ?>">Previous</a>
            <?php else : // NULL case 
            ?>
                Previous
            <?php endif; ?>
        </li>
        <li>
            <?php if ($paginator->next) : ?>
                <a href="<?= $base; ?>?page=<?= $paginator->next; ?>">Next</a>
            <?php else : // NULL case 
            ?>
                Next
            <?php endif; ?>
        </li>
        <li>
            current is page number <?= $paginator->cur_page; ?> from total <?= $paginator->total_page; ?> page.
        </li>
        <li>
            From total <?= $paginator->total_records; ?> record.

        </li>
    </ul>
</nav>
<?php endif; ?>


<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center py-3">

        <!-- at first page -->
        <?php if ($paginator->cur_page == 1) : ?> 
<li class="page-item disabled"><a class="page-link" href="<?= $base; ?>?page=1"> First </a></li>
<li class="page-item active"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->cur_page; ?>"><?= $paginator->cur_page; ?></a></li>
<?php if ($paginator->next <= $paginator->total_page AND $paginator->next) : ?><li class="page-item"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->next; ?>"><?= $paginator->next; ?></a></li><?php endif; ?>
<?php if ($paginator->next+1 <= $paginator->total_page AND $paginator->next) : ?><li class="page-item"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->next+1; ?>"><?= $paginator->next+1; ?></a></li><?php endif; ?>
    <?php if ($paginator->total_page == 1 ) : ?>
        <li class="page-item disabled">
        <?php else : ?> 
            <li class="page-item">
            <?php endif; ?>
        <a class="page-link" href="<?= $base; ?>?page=<?= $paginator->total_page; ?>"> Last </a></li>

        <!-- at last page -->
        <?php elseif ($paginator->cur_page == $paginator->total_page) : ?> 
<li class="page-item"><a class="page-link" href="<?= $base; ?>?page=1"> First </a></li>
<?php if ($paginator->previous-1 > 0) : ?> <li class="page-item "><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->previous-1; ?>"><?= $paginator->previous-1; ?></a></li><?php endif; ?>
<?php if ($paginator->previous > 0) : ?><li class="page-item"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->previous; ?>"><?= $paginator->previous; ?></a></li><?php endif; ?>
<li class="page-item active"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->cur_page; ?>"><?= $paginator->cur_page; ?></a></li>
<li class="page-item disabled"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->total_page; ?>"> Last </a></li>

        <?php else : ?>
<li class="page-item"><a class="page-link" href="<?= $base; ?>?page=1"> First </a></li>
<li class="page-item "><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->previous; ?>"><?= $paginator->previous; ?></a></li>
<li class="page-item active"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->cur_page; ?>"><?= $paginator->cur_page; ?></a></li>
<li class="page-item"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->next; ?>"><?= $paginator->next; ?></a></li>
<li class="page-item"><a class="page-link" href="<?= $base; ?>?page=<?= $paginator->total_page; ?>"> Last </a></li>
        <?php endif; ?>

    </ul>
</nav>