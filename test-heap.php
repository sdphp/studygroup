<?php
// test-heap.php

class FifaRankingHeap extends \SplHeap {
    protected function compare($value1, $value2) {
        return $value1['points'] - $value2['points'];
    }
}

$heap = new FifaRankingHeap();

$heap->insert(array('country' => 'Colombia', 'points' => 1137));
$heap->insert(array('country' => 'Uruguay', 'points' => 1147));
$heap->insert(array('country' => 'Argentina', 'points' => 1175));
$heap->insert(array('country' => 'Brazil', 'points' => 1242));
$heap->insert(array('country' => 'Portugal', 'points' => 1189));
$heap->insert(array('country' => 'Germany', 'points' => 1300));
$heap->insert(array('country' => 'Switzerland', 'points' => 1149));

$i = 2;

foreach ($heap as $country) {
    echo $i++.$country['country'].' has '.$country['points'].' points.';
}
