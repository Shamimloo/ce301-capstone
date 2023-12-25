<?php

if(isset($_POST["selectedLvlArray"])){
    $levelArray = $_POST["selectedLvlArray"];
    $pageID = $_POST["pageID"];
    $count = count($levelArray);
    DB::startTransaction();
    DB::query("DELETE FROM pageLevel WHERE pageID = %i", $pageID);
    $success = DB::affectedRows();
    if($success){
        DB::commit();
    }

   for ($i = 0; $i < $count; $i++) {
        DB::insert('pageLevel',[
            "levelID" => $levelArray[$i],
            "pageID" => $pageID,
        ]);
    }
} elseif(isset($_POST["selectedLvlArray2"])){
    $levelArray = $_POST["selectedLvlArray2"];
    $questionID = $_POST["questionID"];
    $count = count($levelArray);
    DB::startTransaction();
    DB::query("DELETE FROM questionLevel WHERE questionID = %i", $questionID);
    $success = DB::affectedRows();
    if($success){
        DB::commit();
    }

   for ($i = 0; $i < $count; $i++) {
        DB::insert('questionLevel',[
            "levelID" => $levelArray[$i],
            "questionID" => $questionID,
        ]);
    }
}
