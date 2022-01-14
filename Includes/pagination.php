<?php $base = strtok($_SERVER["REQUEST_URI"],'?');?>
 
<nav>
    <ul>
        <li>
            <?php if($paginator->previous): ?>
            <a href="<?=$base;?>?page=<?= $paginator->previous;?>">Previous</a>
            <?php else: // NULL case ?>
                Previous
            <?php endif; ?>
        </li>
        <li>
            <?php if($paginator->next): ?>
                <a href="<?=$base;?>?page=<?= $paginator->next; ?>">Next</a>
            <?php else: // NULL case ?>
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