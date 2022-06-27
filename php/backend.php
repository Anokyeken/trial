<?php
 // Copies all report files from one exam.
$dir = "./";
function read_dir($dir){ // This function is meant to assign a list of file names with their indexes to an array $list.
$list = array();
    if (is_dir($dir)){
        if ($handle = opendir($dir)){ // $handle returns address (handle) of directory.
            while (false !== ($file = readdir($handle))){ // readdir returns a name of the next file in the directory.
                if ($file != "." && $file != ".."){ 
                    $list[] = $file;
                }
            }
        }
    closedir($handle);
    }
return $list;
}

$src="Reports"; // Original reports
$dest="workingreports"; // New reports (directory for copies)
$list= read_dir($src);
$studCount = count($list);
echo "The amount of reports is: ".$studCount."<br>"."<br>"; // For debugging. Delete later.
foreach($list as $key => $val){  
    copy("$src/$val","$dest/$val"); // This bit does the copying using the $list array.
}




$activeMatNum = $_POST['mNumPHP']; // Obtains values from front-end for future use
$activeExamNum = $_POST['eNamePHP']; 
$ePassLim = $_POST['ePassLimPHP'];

if (isset($activeMatNum)) echo "The active matriculation number is set!"."<br>";
else echo "The active matriculation number is not set."."<br>";
if (isset($activeExamNum)) echo "The active exam number is set!"."<br>";
else echo "The active exam number is not set."."<br>";
if (isset($ePassLim))  echo "The active exam passing limit is set!"."<br>";
else echo "The active exam passing limit is not set. Please set the passing limit."."<br>";

/*
Function to adjust a score. The file is stored in an array:
This method is RAM-intensive, but simple and works well for small files (like the ones we're dealing with).
 */
function adjustScore ($score, $questionNum){ 
echo "The selected question to change points for is : ".$questionNum."<br>"; // For debugging. Delete later.
echo "The entered points are: ".$score."<br>"; // For debugging. Delete later.
global $activeMatNum;
global $activeExamNum;


$myString ='./Reports/'.$activeMatNum.'.report'; 
$readArr = file($myString); // Array that contains the scanned file.
$indexArr = array(); // Empty 2D array to store indexes of questions.

$arrCounter = 0;
for ($i=0; $i<count($readArr); $i++){
    if (substr($readArr[$i], 0, 6)=="Punkte"||substr($readArr[$i], 0, 6)=="Points"){
        $indexArr[$arrCounter] = $i; $arrCounter++;
      //  echo "Added!"."<br>"; echo $readArr[$i]."<br>"; echo $indexArr[$arrCounter]."<br>"; // For debugging. Delete later.
    }
}

// print_r($indexArr); // For Debugging. Delete later.
$indexArrSize = count($indexArr);
//echo $readArr[$indexArr[$questionNum]]."<br>"; echo $indexArr[$questionNum]."<br>"; // For debugging. Currently broken.

// echo "The indexArr index is: ".$i."<br>"."The corresponding question number is: "."" //HERE

$myVar = $questionNum-1;
$questionIndex = $indexArr[$myVar]; // Because PHP doesn't allow $readArr[$indexArr[$questionNum-1]]
// The code below inputs the adjusted score by combining the input score and the max score extracted from the same line in the report.
// Also checks if the report is in ENGLISH or GERMAN.
if (substr($readArr[0], 0, 15)=="Matrikelnummer:") { // Checks if the report is German
    if (strpos($readArr[$questionIndex],"/1")) { // Checks what the maximum score is and adjusts the replacement line accordingly.
        $readArr[$questionIndex] = "Punkte:".$score."/1\r\n"; 
        //echo "WIR SIND NUMMER EINS!<br>"; // For debugging. Delete later.
    }
    elseif (strpos($readArr[$questionIndex],"/2")){
        $readArr[$questionIndex] = "Punkte:".$score."/2\r\n";
    }
    elseif (strpos($readArr[$questionIndex],"/3")){
        $readArr[$questionIndex] = "Punkte:".$score."/3\r\n";
    }
    elseif (strpos($readArr[$questionIndex],"/4")){
        $readArr[$questionIndex] = "Punkte:".$score."/4\r\n";
    }
    elseif (strpos($readArr[$questionIndex],"/5")){
        $readArr[$questionIndex] = "Punkte:".$score."/5\r\n";
    }


    }

elseif (substr($readArr[0], 0, 18)=="Enrollment number:"){ // Checks if the report is English
    if (strpos($readArr[$questionIndex],"/1")) { // Checks what the maximum score is and adjusts the replacement line accordingly.
        $readArr[$questionIndex] = "Points:".$score."/1\r\n"; 
        //echo "WE ARE NUMBER ONE!<br>"; // For debugging. Delete later.
    }
    elseif (strpos($readArr[$questionIndex],"/2")){
        $readArr[$questionIndex] = "Points:".$score."/2\r\n";
    }
    elseif (strpos($readArr[$questionIndex],"/3")){
        $readArr[$questionIndex] = "Points:".$score."/3\r\n";
    }
    elseif (strpos($readArr[$questionIndex],"/4")){
        $readArr[$questionIndex] = "Points:".$score."/4\r\n";
    }
    elseif (strpos($readArr[$questionIndex],"/5")){
        $readArr[$questionIndex] = "Points:".$score."/5\r\n";
    }
    }

// This bit just write whatever was in the file-array into the actual working file. Leaves original unmodified.
$writeFile = fopen('./workingreports/'.$activeMatNum.'.report', "w"); // Opens file for write-only. Deletes existing data, creates a new file if needed.
fwrite($writeFile, implode("",$readArr));
fclose($writeFile);
}

function qMaxScore($questionIndex, $studNum){
    $myString ='./workingreports/'.$studNum.'.report'; 
    $readArr = file($myString); // Array that contains the scanned file.
    $indexArr = array(); 
    $arrCounter = 0;
    for ($i=0; $i<count($readArr); $i++){
        if (substr($readArr[$i], 0, 6)=="Punkte"||substr($readArr[$i], 0, 6)=="Points"){
            $indexArr[$arrCounter] = $i; $arrCounter++;
        }
    }
    if (substr($readArr[0], 0, 15)=="Matrikelnummer:") { // Checks if the report is German
        if (strpos($readArr[$questionIndex],"/1")) {
            return 1;
        }
        elseif (strpos($readArr[$questionIndex],"/2")){
            return 2;
        }
        elseif (strpos($readArr[$questionIndex],"/3")){
            return 3;
        }
        elseif (strpos($readArr[$questionIndex],"/4")){
            return 4;
        }
        elseif (strpos($readArr[$questionIndex],"/5")){
            return 5;
        }
        }
    
    elseif (substr($readArr[0], 0, 18)=="Enrollment number:"){ // Checks if the report is English
        if (strpos($readArr[$questionIndex],"/1")) { 
            return 1;
        }
        elseif (strpos($readArr[$questionIndex],"/2")){
            return 2;
        }
        elseif (strpos($readArr[$questionIndex],"/3")){
            return 3;
        }
        elseif (strpos($readArr[$questionIndex],"/4")){
            return 4;
        }
        elseif (strpos($readArr[$questionIndex],"/5")){
            return 5;
        }
        }
}


$myString = 'workingreports/'.$activeMatNum.'.report';
$readOutArr = file($myString);
/*
foreach ($readOutArr as $lineNum){  //Outputs written file contents in browser for debugging purposes. Delete later.
    echo  nl2br($lineNum);
}*/


// Function to read all reports in an exam and return the maximum possible score.
function readMaxScore($examNum){ 
    global $studCount;
    //print_r($indexArr); // For debugging. Delete later.    
    $maxScore = 0;
    for ($ii=1;$ii<=$studCount;$ii++){
        // echo "The current report number is: ".$ii."<br>"; // For debugging. Delete later.
        $myString ='./workingreports/'.$ii.'.report'; 
        $readArr = file($myString); // Array that contains the scanned file.
        $indexArr = array(); 
        $arrCounter = 0;
        for ($i=0; $i<count($readArr); $i++){
            if (substr($readArr[$i], 0, 6)=="Punkte"||substr($readArr[$i], 0, 6)=="Points"){
                $indexArr[$arrCounter] = $i; $arrCounter++;
               // echo "Added!"."<br>"; echo $readArr[$i]."<br>"; echo $indexArr[$arrCounter]."<br>"; // For debugging. Delete later.
            }
        }
        $i = 0; 
        while($i < count($indexArr)){
            $myVar = $indexArr[$i];
            if (strpos($readArr[$myVar],"/1")) { 
                $maxScore+=1; 
            }
            elseif (strpos($readArr[$myVar],"/2")){
                $maxScore+=2; 
            }
            elseif (strpos($readArr[$myVar],"/3")){
                $maxScore+=3; 
            }
            elseif (strpos($readArr[$myVar],"/4")){
                $maxScore+=4; 
            }
            elseif (strpos($readArr[$myVar],"/5")){
                $maxScore+=5; 
            }
            /*
            echo "Counter is ".$i."<br>";
            echo $maxScore."<br>"; // For debugging. Delete later.
            echo "myVar ".$myVar."<br>";
            echo "Scanned line is: "."$readArr[$myVar]"."<br>"."<br>";
            echo "IndexArrAccesing: ".$indexArr[$i]."<br>";
            */
            $i+=1;
                // All of this for debugging. Delete later.
            
        }
    }
    return $maxScore;
}

// Function to read all obtained points in an exam (by all students collectively).
function readObtScore($examNum){
    global $studCount;
    $obtScore = 0;
    for ($ii=1;$ii<=$studCount;$ii++){
        // echo "The current report number is: ".$ii."<br>"; // For debugging. Delete later.
        $myString ='./workingreports/'.$ii.'.report'; 
        $readArr = file($myString); // Array that contains the scanned file.
        $indexArr = array(); // Empty 2D array to store indexes of questions.
        $arrCounter = 0;
        for ($i=0; $i<count($readArr); $i++){
            if (substr($readArr[$i], 0, 6)=="Punkte"||substr($readArr[$i], 0, 6)=="Points"){
                $indexArr[$arrCounter] = $i; $arrCounter++;
            // echo "Added!"."<br>"; echo $readArr[$i]."<br>"; echo $indexArr[$arrCounter]."<br>"; // For debugging. Delete later.
            }
        }
        $i=0; 
        while ($i < count($indexArr)){
            $myVar = $indexArr[$i];
            $extractedScore = findScoreInLine($readArr[$myVar], ":", "/");
            $obtScore+=floatval($extractedScore);
            // echo "Extracted score is: ".$extractedScore."<br>"; // For debugging. Delete later.
            // echo "Obtained total score is: ".$obtScore."<br>"; // For debugging. Delete later.
            // echo $readArr[$myVar].'<br>'; // For debugging. Delete later.
            $i++;
        } 
    }
    return $obtScore;
}

// Used to extract obtained points from a string. Takes the string, beginning symbol(-s) and ending symbol(-s).
function findScoreInLine($string, $start, $end){
    $string = ' ' . $string; 
    $ini = strpos($string, $start);
    if ($ini == 0) return ''; 
    $ini += strlen($start); 
    $len = strpos($string, $end, $ini) - $ini; 
    return floatval(str_replace(',', '.', substr($string, $ini, $len))) ;
    
}

function findStringInLine($string, $start, $end){
    $string = ' ' . $string; 
    $ini = strpos($string, $start);
    if ($ini == 0) return ''; 
    $ini += strlen($start); 
    $len = strpos($string, $end, $ini) - $ini; 
    return substr($string, $ini, $len);
    
}

// Returns the maximum possible score for the student.
function maxScoreOfStudent($studNum){  
    $maxScore = 0;
        $myString ='./workingreports/'.$studNum.'.report'; 
        $readArr = file($myString); // Array that contains the scanned file.
        $indexArr = array(); // Empty 2D array to store indexes of questions.
        $arrCounter = 0;
        for ($i=0; $i<count($readArr); $i++){
            if (substr($readArr[$i], 0, 6)=="Punkte"||substr($readArr[$i], 0, 6)=="Points"){
                $indexArr[$arrCounter] = $i; $arrCounter++;
            }
        }
        $i = 0; 
        while($i < count($indexArr)){
            $myVar = $indexArr[$i];
            if (strpos($readArr[$myVar],"/1")) { 
                $maxScore+=1; 
            }
            elseif (strpos($readArr[$myVar],"/2")){
                $maxScore+=2; 
            }
            elseif (strpos($readArr[$myVar],"/3")){
                $maxScore+=3; 
            }
            elseif (strpos($readArr[$myVar],"/4")){
                $maxScore+=4; 
            }
            elseif (strpos($readArr[$myVar],"/5")){
                $maxScore+=5; 
            }
            $i+=1;
        }
    return $maxScore;
}

// Returns the obtained score of the selected student.
function obtScoreOfStudent($studNum){
    $obtScore = 0;
    $myString ='./workingreports/'.$studNum.'.report'; 
    $readArr = file($myString); // Array that contains the scanned file.
    $indexArr = array(); // Empty 2D array to store indexes of questions.
    $arrCounter = 0;
    for ($i=0; $i<count($readArr); $i++){
        if (substr($readArr[$i], 0, 6)=="Punkte"||substr($readArr[$i], 0, 6)=="Points"){
            $indexArr[$arrCounter] = $i; $arrCounter++;
        }
    }
    $i=0; 
    while ($i < count($indexArr)){
        $myVar = $indexArr[$i];
        $extractedScore = findScoreInLine($readArr[$myVar], ":", "/");
        $obtScore+=floatval($extractedScore);
        $i++;
        // echo "Extracted score is: ".$extractedScore."<br>"; // For debugging. Delete later.
        // echo "Obtained total score is: ".$obtScore."<br>"; // For debugging. Delete later.
        // echo $readArr[$myVar].'<br>'; // For debugging. Delete later.
    } 
    return $obtScore;    
}

// Function to find out how many students passed.
function passedStudentCount(){
    global $studCount;
    global $ePassLim;
    $passedCount=0;
    for ($i=1; $i<=$studCount; $i++){
        if(maxScoreOfStudent($i)*$ePassLim < obtScoreOfStudent($i)){
            $passedCount+=1;
            // echo "Student ".$i." passed with a score of ".obtScoreOfStudent($i)." out of ".maxScoreOfStudent($i)."<br>"; // Debugging.
        }
    }
    return $passedCount;
}

// Function to find out how many students failed.
function failedStudentCount(){
    global $studCount;
    global $ePassLim;
    $failCount=0;
    for ($i=1; $i<=$studCount; $i++){
        if(maxScoreOfStudent($i)*$ePassLim > obtScoreOfStudent($i)){
            $failCount+=1;
            // echo "Student ".$i." failed with a score of ".obtScoreOfStudent($i)." out of ".maxScoreOfStudent($i)."<br>"; // Debugging.
        }
    }
    return $failCount;
}

// Returns the average score of the entire exam.
function averageScore(){
    global $studCount;
    $total=0;
    for ($i=1; $i<=$studCount; $i++){
        $total+=obtScoreOfStudent($i);
    }
    return ($total/$studCount);
}

// Returns the highest score in the exam.
function hScore(){
    global $studCount;
    $hScore=0;
    for ($i=1; $i<=$studCount; $i++){
        if($hScore<obtScoreOfStudent($i)){
        $hScore=obtScoreOfStudent($i);
        }
    }
    return $hScore;
}

// Returns the lowest score in the exam.
function lScore(){
    global $studCount;
    $lScore=obtScoreOfStudent(1);
    for ($i=1; $i<=$studCount; $i++){
        if($lScore>obtScoreOfStudent($i)){
        $lScore=obtScoreOfStudent($i);
        }
    }
    return $lScore;
}

/* 
Returns the number of the question which students failed the most.
 This function can later be modified to return the best answered questions(-s)
 and/or return statistics numbers about the exam-wide scores.
 */
function mostFailedQuestion(){
    global $studCount;
    $obtScore = 0;
    $failedQArr = array(); // Empty array for storing questions and the amounts of times they were failed.
    for ($ii=1;$ii<=$studCount;$ii++){
        $myString ='./workingreports/'.$ii.'.report'; 
        $readArr = file($myString); // Array that contains the scanned file.
        $indexArr = array(); // Empty array to store indexes of questions.
        $arrCounter = 0;
        for ($i=0; $i<count($readArr); $i++){
            if (substr($readArr[$i], 0, 6)=="Punkte"||substr($readArr[$i], 0, 6)=="Points"){
                $indexArr[$arrCounter] = $i; $arrCounter++;
            }
        }
        $i=0; 

        $failedQArr[0]=0;
        while ($i < count($indexArr)){
             //echo "Current tested question: ".$i."<br>"; // Debugging
            $myVar = $indexArr[$i];
            $extractedScore = findScoreInLine($readArr[$myVar], ":", "/");
            if(floatval($extractedScore)==0){
                if((array_key_exists($i, $failedQArr))===FALSE){
                    $failedQArr[$i] = 0;
                     //echo "Assigned!"."<br>"; // Debugging.
                }
                if(array_key_exists($i, $failedQArr)){
                    $failedQArr[$i] = $failedQArr[$i] += 1;
                    // echo "Incremented!"."<br>"."<br>"; // Debugging.
                }
                //echo "Failed!"."<br>";
            }
            $i++;
        } 
    }
    $i=1;
    $mostFailedQ=0;
    $thiccestValue=0;
    while($i < (count($failedQArr))){
    $myVar = $i-1;
        if($myVar<47){
            if (($failedQArr[$i]<$failedQArr[$myVar]) && ($thiccestValue<$failedQArr[$myVar])){
                $mostFailedQ = $myVar;
                $thiccestValue = $failedQArr[$myVar];
            }
            
        }
        
        // echo "Question: ".($i)."<br>"; // For debugging. Delete later.
       //  echo "Times failed: ".$failedQArr[$i]."<br>"; // For debugging. Delete later.
       //  echo "Current most failed question: ".($mostFailedQ)."<br>"."<br>"; // For debugging. Delete later.
        
        $i++;
    } 
    return ($mostFailedQ+1);
}

// Returns TRUE if the currently selected student passed the exam.
function ifStudentPassed($studNum){
    global $ePassLim;
    if(obtScoreOfStudent($studNum)>=(maxScoreOfStudent($studNum)*$ePassLim)){
        return TRUE;
    }
    else return FALSE;
}


if(ifStudentPassed($activeMatNum)===TRUE){ // Debugging.
    echo "The selected student passed with a score of ".obtScoreOfStudent($activeMatNum)." out of ".maxScoreOfStudent($activeMatNum)."<br>"."<br>"; 
}
else{
    echo "The selected student failed with a score of ".obtScoreOfStudent($activeMatNum)." out of ".maxScoreOfStudent($activeMatNum)."<br>"."<br>"; 
}
echo "The total possible score in the exam is: ".readMaxScore(0)."<br>"; // For debugging. Delete later.
echo "The obtained score (collectively) in the exam is: ".readObtScore(0)."<br>"; // For debugging. Delete later.
echo "Students passed: ".passedStudentCount()."<br>"; // Debugging.
echo "Students failed: ".failedStudentCount()."<br>"; // Debugging.
echo "Average obtained score in this exam: ".round(averageScore(),3)."<br>"; // Debugging.
echo "Lowest score obtained: ".lScore()."<br>"; // Debugging.
echo "Highest score obtained: ".hScore()."<br>"; // Debugging.
echo "Most failed question: ".mostFailedQuestion()."<br>"; // Debugging.
if (isset($_POST["scorePHP"]) || isset($_POST["qNumPHP"]))
adjustScore($_POST["scorePHP"], $_POST["qNumPHP"]); 


// Function to encode working report data to a json file.
function dbEncode(){
global $studCount;

$myArr = Array(Array());
$totalCounter = 0;
for ($ii=1;$ii<=$studCount;$ii++){
    
    // echo "I'm WORKING!"."<br>"; // Debugging.
    $myString ='./workingreports/'.$ii.'.report'; 
    $readArr = file($myString); // Array that contains the scanned file.
    $questionIndexArr = array(); 
    $pIndexArr = array(); 
    $cAnswerIndexArr = array();
    $selAnswerIndexArr = array();

    

    $arrCounter = 0;
    for ($i=0; $i<count($readArr); $i++){
        if (substr($readArr[$i], 0, 6)=="Punkte"||substr($readArr[$i], 0, 6)=="Points"){
            $pIndexArr[$arrCounter] = $i; $arrCounter++;
            // echo "Question is: ".($arrCounter)."<br>"; // Debugging.
            // echo "Index is: ".$i."<br>"; // Debugging.
        }
    }

    $arrCounter = 0;
    for ($i=0; $i<count($readArr); $i++){
        if (substr($readArr[$i], 0, 12)=="Ihre Antwort"||substr($readArr[$i], 0, 13)=="Your response"){
            $selAnswerIndexArr[$arrCounter] = $i; $arrCounter++;
        }
    }

    $arrCounter = 0;
    for ($i=0; $i<count($readArr); $i++){
        if (substr($readArr[$i], 0, 15)=="Korrekte Anwort"||substr($readArr[$i], 0, 14)=="Correct Answer"){
            $cAnswerIndexArr[$arrCounter] = $i; $arrCounter++;
        } 
    }   

    $arrCounter = 0;
    for ($i=0; $i<count($readArr); $i++){
        // echo $readArr[$i]."<br>"; // Debugging.
        if (substr($readArr[$i], 0, 5)=="Frage"||substr($readArr[$i], 0, 8)=="Question"){
            $questionIndexArr[$arrCounter] = $i; $arrCounter++;
        }
        elseif(strpos($readArr[$i], "Frage")||strpos($readArr[$i], "Question")){ // Added this because substr() missed one question for some unknown reason.
            $questionIndexArr[$arrCounter] = $i; $arrCounter++;
        }

    }

for($i=0; $i<count($readArr); $i++){ // Removes line break special characters from the file.
    $readArr[$i] = str_replace(array("\r", "\n"), '', $readArr[$i]);
}

    for($i=1; $i <= count($pIndexArr); $i++){
        $pIndex = $pIndexArr[$i-1]; 
        //echo "Current point line: ".$readArr[$pIndex]."<br>"; // Debugging.
        $cAIndex = $cAnswerIndexArr[$i-1];
        //echo "Current given answer: ".$readArr[$cAIndex]."<br>"; // Debugging.
        $selAIndex = $selAnswerIndexArr[$i-1];
        //echo "Current correct answer: ".$readArr[$selAIndex]."<br>"; // Debugging.
        $qIndex = $questionIndexArr[$i-1];
        $myArr[$totalCounter] =
            Array (
            "matriculation_id" => $ii, // Matriculation number
            "question_number" => "(".(findStringInLine($readArr[$qIndex],": (",")")).") ".$i.")", // Question number
            "question" => str_replace("(".(findStringInLine($readArr[$qIndex],": (",")")).") ".$i.")", '', $readArr[$qIndex]), // Question text
            "correct_answer" => $readArr[$cAIndex], // Correct answer
            "chosen_answer" => $readArr[$selAIndex], // Given asnwer
            "score" => findScoreInLine($readArr[$pIndex],":","/"), // Score assigned for this question
            "maximum_score" => qMaxScore($pIndex, $ii), // Maximum score of answer
            );
        $totalCounter++;
    }
    
}

// Encode array to json
$json = json_encode(array('data' => $myArr), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_LINE_TERMINATORS);
// Write json to file
if (file_put_contents("../db/people.json", $json))
    echo "JSON file created successfully..."."<br>";
else 
    echo "Oops! Error creating json file..."."<br>";
}

function encodeStatistics(){
    $myArr =   Array (
                "students_passed" => passedStudentCount(), // 
                "students_failed" => failedStudentCount(), //
                "average_score" => round(averageScore(),3), // 
                "lowest_score" => lScore(), // 
                "highest_score" => hScore(), // 
                "most_failed_question" => mostFailedQuestion(), // 
        );

// Encode array to json
$json = json_encode(array('data' => $myArr), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_LINE_TERMINATORS);
// Write json to file
if (file_put_contents("../db/statistics.json", $json))
    echo "JSON file created successfully..."."<br>";
else 
    echo "Oops! Error creating json file..."."<br>";
}

// Function to encode individual student data into a json
function encodeIndividualInfo(){
    global $studCount;
    for ($ii=1;$ii<=$studCount;$ii++){
        if(ifStudentPassed($ii)==TRUE){
            $studStatus = "passed";
        }
        else{
            $studStatus = "failed";
            // echo "Well I didn't want this to work anyway"."<br>"; // Debugging
        }
        
        $myArr[$ii] =
        Array (
            "matriculation_id" => $ii, //
            "status" => $studStatus, // 
            "obtained_score" => round(obtScoreOfStudent($ii), 1), //
            "maximum score" => maxScoreOfStudent($ii), //             
        );
    }

// Encode array to json
$json = json_encode(array('data' => $myArr), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_LINE_TERMINATORS);
// Write json to file
if (file_put_contents("../db/individual_statistics.json", $json))
    echo "JSON file created successfully..."."<br>";
else 
    echo "Oops! Error creating json file..."."<br>";
}


dbEncode();
encodeStatistics();
encodeIndividualInfo();

?>