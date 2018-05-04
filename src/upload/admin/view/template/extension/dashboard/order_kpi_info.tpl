<style type="text/css">
    .t-abs{
        color: green;
    }
    .t-rel {
        color:#0E6A93;
    }
    .t-sum {
        color: blueviolet;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-calendar"></i> <?php echo $heading_title; ?></h3>
    </div>

    <table cellpadding="5" cellspacing="0" border="0" class="table table-bordered table-striped table-responsive">
        <thead>
            <tr>
                <th rowspan="2" class="text-center"><?php echo $text_order_status;?></th>
                <?php foreach ($head as $year) : ?>
                    <th colspan="<?=count($year['month']);?>" class="text-center"><?=$year['year']?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($head as $year) : ?>
                    <?php foreach ($year['month'] as $m) : ?>
                        <th class="text-center"><?=$m;?></th>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row) : ?>
                <tr>
                    <td><?=$order_statuses[$row['order_status_id']];?></td>
                    <?php foreach ($head as $year) : ?>
                        <?php foreach ($year['month'] as $month) : ?>
                            <td class="text-right">
                                <?php if (isset($row[$year['year']][$month])) : ?>
                                    <span class="t-rel"><?php echo round($row[$year['year']][$month]['count'] / $totals[$year['year'] . ':' . $month]['count'] * 100 ) ; ?>%</span><br />
                                    <span class="t-abs"><?php echo $row[$year['year']][$month]['count'];?></span><br />
                                    <span class="t-sum"><?php echo $row[$year['year']][$month]['sum'];?></span>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <th><?php echo $text_total; ?></th>
		        <?php foreach ($head as $year) : ?>
			        <?php foreach ($year['month'] as $month) : ?>
                        <td class="text-right">
                            <span class="t-abs"><?php echo $totals[$year['year'] . ':' . $month]['count'];?></span><br />
                            <span class="t-sum"><?php echo round($totals[$year['year'] . ':' . $month]['sum']);?></span>
                        </td>
			        <?php endforeach; ?>
		        <?php endforeach; ?>
            </tr>
            <tr>
	            <?php $colspan = 0; foreach ($head as $y) $colspan += count($y['month']);?>
                <td colspan="<?=$colspan;?>">
                    <?php echo $text_legend;?>
                    <span class="t-rel"><?php echo $text_percent; ?></span>,
                    <span class="t-abs"><?php echo $text_absolute;?></span>,
                    <span class="t-sum"><?php echo $text_sum;?></span>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
