<ul class="pagination">
    <?php if ($prev === false): ?>
        <li class="disabled"><a href="javascript:;">&laquo;</a></li>
    <?php else: ?>
        <li><a href="<?php echo $this->escape(sprintf($url, $page - 1)); ?>">&laquo;</a></li>
    <?php endif; ?>

    <?php for ($p = $from; $p <= $to; $p++) : ?>
        <li<?php if ($p == $page): ?> class="active"<?php endif; ?>>
            <a href="<?php echo $this->escape(sprintf($url, $p)); ?>"><?php echo (int)$p; ?></a>
        </li>
    <?php endfor; ?>

    <?php if ($next === false): ?>
        <li class="disabled"><a href="javascript:;">&raquo;</a></li>
    <?php else: ?>
        <li><a href="<?php echo $this->escape(sprintf($url, $page + 1)); ?>">&raquo;</a></li>
    <?php endif; ?>

</ul>