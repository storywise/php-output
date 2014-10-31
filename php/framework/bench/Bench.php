<?php

/**
 * Description of Bench
 * @author merten
 */
class Bench {

        public static $LIST = array();
        public static $LOG = '';

        public static function mark($event) {

                if (!LOCAL)
                        return false;

                // Get current microtime
                $time = (float) microtime(true);

                // When did we start benchmarking
                $startTime = isset(Bench::$LIST[0]) ? Bench::$LIST[0]['time'] : $time;

                // Calculate time since the start
                $tSinceStart = $time - (float) $startTime;

                // Id of previous benchmark entry
                $idPrev = count(Bench::$LIST) - 1;

                // Time spanned since that entry
                $tSinceLast = $idPrev >= 0 ? $time - Bench::$LIST[$idPrev]['time'] : 0;

                // Log current event and time
                array_push(Bench::$LIST, array('sinceStart' => $tSinceStart, 'sinceLast' => $tSinceLast, 'time' => $time, 'event' => $event));
        }

        public static function output($getVar = false) {

                $log = "\n\n<!-- Benchmark in order of appearance:\n ";

                $sortByTime = array();
                foreach (Bench::$LIST as $id => $row) {
                        $event = $row['event'];
                        $tSinceLast = $row['sinceLast'];
                        $tSinceStart = $row['sinceStart'];
                        // Add to output string
                        $log .= "\n\t\t$event ( " . number_format($tSinceLast, 5) . "s [ " . number_format($tSinceStart, 5) . " ] )";
                        
                        // Prepare to sort by sinceLast exec time
                        $sortByTime[$id] = $row['sinceLast'];
                }

                $log .= "\n-->\n";
                
                // Sort by tSinceLast
                array_multisort($sortByTime, SORT_DESC, Bench::$LIST);
                
                $log .= "<!-- Benchmark ordered by execution time:\n ";
                
                foreach (Bench::$LIST as $id => $row) {
                        $event = $row['event'];
                        $tSinceLast = $row['sinceLast'];
                        $tSinceStart = $row['sinceStart'];
                        // Add to output string
                        $log .= "\n\t\t$event " . number_format($tSinceLast, 5).'s';
                }
                
                $log .= "\n-->";

                if (!LOCAL)
                        return false;

                if ($getVar)
                        return $log . "\n\n";
                else
                        echo $log . "\n\n";
        }

}

?>