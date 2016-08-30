<?PHP

function AddCount (){
	
	global $NewCount;
 	
	$TheFile ="counter.txt";

	$Open = fopen ($TheFile, "r");
	if ($Open){
		$Data = file($TheFile);
		$Count = $Data[0];
		$NewCount =$Count + 1;
		return $NewCount;
		
		fclose ($Open);
}



} // End AddCount

function AddCountFile (){
	
	global $NewCount;
	$TheFile ="counter.txt";

	$Open = fopen ($TheFile, "w");
	if ($Open){
		
		fwrite ($Open, "$NewCount\n");
		
		fclose ($Open);
}
}
AddCount();
AddCountFile();




?>



