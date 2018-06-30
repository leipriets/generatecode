<?php 
function generateStudNo($schYear, $sem, $branch, $deptType = 'C')
{
    global $semArray, $siteCode, $inBedDepts;
    # $courseCode = "";
    $lastdept = array("D", "G", "J", "L");
    /** these department is from cavite */
    /*
    PWU Student Number convention:
      A    -
      99   - last two-digit of the school year
      9    - numerical equivalent of the semester (A - 1, B - 2)
      9999 - series
  */
    $result = "";
    $seriesNo = 0;

    $extend = $deptType == "F" ? "G" : "";

    # First two-digit of the school
    /*
   * 02282015
   * Changes from {SY}-{DEPARTMENT}{SERIES}
    $twoDigit = date('Y');
    $twoDigit .= "-";
    $twoDigit .= $extend;
    $s = 5;
   */

    /**
     * New student # template
     * 02282015
     * Aaron P. Ruanto
     */

    /** if the department is College of Law and the site is Manila, the series length will be 6, 5 if not */
    #$s = ($deptType=="L" && $siteCode==1?6:5);
    $s = 4;

    # $twoDigit = date('Y');
    # $twoDigit = substr($schYear,0,2);
    $twoDigit = substr($schYear, 0, 2) + 2000;
    $letterExtension = $branch;
    #$twoDigit .= substr($letterExtension, 0, 1);
    #$twoDigit .= "-";
    if(in_array($deptType,$inBedDepts)){
        if($branch == 'ET' ) {
            $letterExtension = "E";
        }else if(checkIfUserIsCDCEC($branch)){
             switch($branch){
                case "C1":
                $letterExtension = "BG";
                break;
                case "C2":
                $letterExtension = "BN";
                break;
                case "C3":
                $letterExtension = "CM";
                break;
                case "C4":
                $letterExtension = "CN";
                break;
                case "C5":
                $letterExtension = "SC";
                break;
                case "C6":
                $letterExtension = "TR";
                break;
             }
        }else{
            $letterExtension = substr($letterExtension, 0, 1);
        }
        // $twoDigit .= $letterExtension;
    }else{
        // $twoDigit .= substr($letterExtension, 0, 1);
        $letterExtension = substr($letterExtension, 0, 1);
    }

    #$twoDigit .= "-";
    $check = 0;

    $sql = "SELECT SeqNo FROM tblStudNo a WHERE SY='$schYear' AND Code='$branch'";
    #echo $sql."<br>";
    $rsMax = safe_query($sql);

    $seriesNo = 0;
    if ($countName = mysql_fetch_array($rsMax)) {
        $seriesNo = (int)$countName['SeqNo'];
    }

    while ($check != 1) {
        $seriesNo++;
        $seriesNoTransform = reformString($seriesNo, '0', $s);
        $sql_c = safe_query("SELECT StudNo FROM tblPersonalData WHERE StudNo='{$twoDigit}-{$seriesNoTransform}{$letterExtension}'");
        if (mysql_num_rows($sql_c) == 0) $check = 1;
    }


    $sql = "REPLACE INTO tblStudNo (SY, SeqNo, TransDate, Code, CourseCode) " .
        "VALUES ('$schYear','$seriesNo',SYSDATE(), '$branch', '')";
    #echo $sql;
    safe_query($sql);
    $seriesNoTransform = reformString($seriesNo, '0', $s);

    # {SY} - {SERIES} - {SITE} 
    $result = "{$twoDigit}-{$seriesNoTransform}{$letterExtension}";
    return $result;
}


?>
