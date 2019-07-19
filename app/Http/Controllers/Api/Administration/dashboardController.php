<?php
namespace App\Http\Controllers\Api\Administration;


use App\Http\Controllers\Controller;
use App\post;
use Illuminate\Http\Request;


class dashboardController extends Controller {
    public function index(Request $request){
        $response = [];
        $bytes = disk_free_space(".");
        $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
        $base = 1024;
        $class = min((int)log($bytes , $base) , count($si_prefix) - 1);
        $response['DDLibre'] = sprintf('%1.2f' , $bytes / pow($base,$class));
        $bytes = disk_total_space(".");
        $exp = floor(log($bytes)/log(1024));
        $response['DDTotal'] =  sprintf('%.2f ', ($bytes/pow(1024, floor($exp))));
        $response['DDUniter'] = $si_prefix[$exp];
        function getServerMemoryUsage($getPercentage=true)
        {
            $memoryTotal = null;
            $memoryFree = null;

            if (stristr(PHP_OS, "win")) {
                // Get total physical memory (this is in bytes)
                $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
                @exec($cmd, $outputTotalPhysicalMemory);

                // Get free physical memory (this is in kibibytes!)
                $cmd = "wmic OS get FreePhysicalMemory";
                @exec($cmd, $outputFreePhysicalMemory);

                if ($outputTotalPhysicalMemory && $outputFreePhysicalMemory) {
                    // Find total value
                    foreach ($outputTotalPhysicalMemory as $line) {
                        if ($line && preg_match("/^[0-9]+\$/", $line)) {
                            $memoryTotal = $line;
                            break;
                        }
                    }

                    // Find free value
                    foreach ($outputFreePhysicalMemory as $line) {
                        if ($line && preg_match("/^[0-9]+\$/", $line)) {
                            $memoryFree = $line;
                            $memoryFree *= 1024;  // convert from kibibytes to bytes
                            break;
                        }
                    }
                }
            }
            else
            {
                if (is_readable("/proc/meminfo"))
                {
                    $stats = @file_get_contents("/proc/meminfo");

                    if ($stats !== false) {
                        // Separate lines
                        $stats = str_replace(array("\r\n", "\n\r", "\r"), "\n", $stats);
                        $stats = explode("\n", $stats);

                        // Separate values and find correct lines for total and free mem
                        foreach ($stats as $statLine) {
                            $statLineData = explode(":", trim($statLine));

                            //
                            // Extract size (TODO: It seems that (at least) the two values for total and free memory have the unit "kB" always. Is this correct?
                            //

                            // Total memory
                            if (count($statLineData) == 2 && trim($statLineData[0]) == "MemTotal") {
                                $memoryTotal = trim($statLineData[1]);
                                $memoryTotal = explode(" ", $memoryTotal);
                                $memoryTotal = $memoryTotal[0];
                                $memoryTotal *= 1024;  // convert from kibibytes to bytes
                            }

                            // Free memory
                            if (count($statLineData) == 2 && trim($statLineData[0]) == "MemFree") {
                                $memoryFree = trim($statLineData[1]);
                                $memoryFree = explode(" ", $memoryFree);
                                $memoryFree = $memoryFree[0];
                                $memoryFree *= 1024;  // convert from kibibytes to bytes
                            }
                        }
                    }
                }
            }

            if (is_null($memoryTotal) || is_null($memoryFree)) {
                return null;
            } else {
                if ($getPercentage) {
                    return (100 - ($memoryFree * 100 / $memoryTotal));
                } else {
                    return array(
                        "total" => $memoryTotal,
                        "free" => $memoryFree,
                    );
                }
            }
        }
        function getNiceFileSize($bytes, $binaryPrefix=true) {
            if ($binaryPrefix) {
                $unit=array('B','KB','MB','GB','TB','PB');
                if ($bytes==0) return '0 ' . $unit[0];
                return @round($bytes/pow(1024,($i=floor(log($bytes,1024)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
            } else {
                $unit=array('B','KB','MB','GB','TB','PB');
                if ($bytes==0) return '0 ' . $unit[0];
                return @round($bytes/pow(1000,($i=floor(log($bytes,1000)))),2) .' '. (isset($unit[$i]) ? $unit[$i] : 'B');
            }
        }
        $memUsage = getServerMemoryUsage(false);
        $response['memoryUtil'] = getNiceFileSize($memUsage["total"] - $memUsage["free"]);
        $response['memoryTotal'] = getNiceFileSize($memUsage["total"]);
        $response['memoryPourcentage'] = getServerMemoryUsage(true);
        return $response;

    }

    public function show(Request $request){

    }


    public function create(request $request){

    }
    public function update(request $request){

    }
    public function delete(request $request){


    }


}