<?php 


class GenerateRefCode {

	public function reformString($stringToFill = '', $fillString = '0', $lengthOfString = 2, $alignRight = true)
	{
	    (substr($stringToFill, -1) == 'C' ? $lengthOfString++ : '');
	    $result = $stringToFill;
	    for ($c = 1; $c <= $lengthOfString - strlen($stringToFill); $c++) {
	        if ($alignRight) $result = $fillString . $result;
	        else $result .= $fillString;
	    }
	    return $result;
	}

	public function generatecode($code){

		$date = date("Y-m-d");
		$index = 4;
		$refcode = 0;


		$codetoTransform = $this->reformString($refcode++,'0',$index);

		$return = '{$date}{$codetoTransform}';

		return $return;

	}



}


?>


<?php if (isset($_POST['txtcode']) AND !empty($_POST['txtcode']) ){
	GenerateRefCode::generatecode($_POST['txtcode']);
} 

echo GenerateRefCode::generatecode($_POST['txtcode']); 


?>	




<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
</head>
<body>

	<form class="form form-horizontal" method="post" action="<?= $_SERVER['PHP_SELF'] ?>">		
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="form-group"> 
						 <input type="text" class="form-control" name="txtcode">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group"> 
						<button type="submit" class="btn btn-primary">
							Submit
						</button>
					</div>
				</div>
			</div>

		</div>
	</form>


</body>
</html>