<?php
if(!empty($_POST)){
	include("fun.inc.php");
	$file = fopen(CSV_FILE, 'r');
	$data = array();
	while($row = fgetcsv($file)) {
	   $data[] = $row;
	}
	
	$total_lines = count($data);

	$head[] = $data[0];

	$failed_data = array();

	$primarydb = $_POST['primarydb'];
	$primarydb_count = count($primarydb);

	$primarykey = $_POST['primarykey'];

	$columns = $_POST['columns_a'];
	$columns_db = $_POST['columns_b'];
	$columns_count = count($_POST['columns_a']);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>[Output] Punch - App to update data from CSV to MySQL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 10px auto;
        padding: 30px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="ico/favicon.png">
  </head>

  <body>

    <div class="container-narrow">

      <div class="masthead">
        <h3 class="muted">Punch from CSV to MySQL</h3>
      </div>

      <hr>

      <div class="row-fluid marketing">
        <div class="tabbable"> <!-- Only required for left/right tabs -->
			  <ul class="nav nav-tabs">
			    <li class="active"><a href="#tab1" data-toggle="tab">Output</a></li>
			    <li ><a href="#tab2" data-toggle="tab">Failure</a></li>
			  </ul>
			  <div class="tab-content">
			    <div class="tab-pane active" id="tab1">
			      <table class="table table-bordered">
		              <thead>
		                <tr>
		                	<th>Sl. No.</th>
		                <?php
		                	
		                	for($p=0;$p<$columns_count;$p++){
		                		echo '<th>'.$columns_db[$p].'</th>';
		                	}
		                ?>
		                	<th>Status</th>
		                </tr>
		              </thead>
		              <tbody>
		                
		                  <?php
		                  	for($j=1;$j<$total_lines;$j++){
								//Prepare SQL
								echo '<tr><th>'.$j.'</th>';
								$cnt = 1;
								$where = '';

								//WHERE CONDITION
								for($p=0;$p<$primarydb_count;$p++){
									$k = $primarykey[$p];
									if($cnt < $primarydb_count){
										$where .=  $primarydb[$p]." LIKE '".$data[$j][$k]."' AND";
									}else{
										$where .=  $primarydb[$p]." LIKE '".$data[$j][$k]."'";
									}
									
									$cnt = $cnt + 1;	
								}

								//DATA UPDATE
								$cnt = 1;
								$data_op = '';
								for($d=0;$d<$columns_count;$d++){
									$ds = $columns_db[$d];
									$dk = $columns[$d];
									if($cnt < $columns_count){
										$data_op .= "`".$ds."` = '".$data[$j][$dk]."',";
									}else{
										$data_op .= "`".$ds."` = '".$data[$j][$dk]."'";
									}
									echo "<th>".$data[$j][$dk]."</th>";
									$cnt = $cnt + 1;
								}
								$table = DB_TABLE;

								$op = update_simple($table,$data_op,"WHERE ".$where);
								if($op){
									echo '<th><span class="label label-success">Updated</span></th>';
								}else{
									$failed_data[] = $data[$j];
									echo '<th><span class="label label-warning">Failed</span></th>';
								}
								echo '</tr>';
							}
		                  ?>
		                
		                
		              </tbody>
		            </table>
			    </div>
			    <div class="tab-pane" id="tab2">
			      <table class="table table-bordered">
		              <thead>
		                <tr>
		                <?php
		                	$h_len = count($head[0]);
		                	$head_d = $head[0];
		                	for($hc=0;$hc<$h_len;$hc++){
		                		echo '<th>'.$head_d[$hc].'</th>';
		                	}
		                ?>
		                </tr>
		              </thead>
		              <tbody>
		                
		                  <?php
		                  if(!empty($failed_data)){
		                  	$h_len = count($failed_data);
		                  	for($hc=0;$hc<$h_len;$hc++){
		                  		$h_len_e = count($failed_data[$hc]);
		                  		echo '<tr>';
		                  		for($x=0;$x<$h_len_e;$x++){
		                  			echo '<th>'.$failed_data[$hc][$x].'</th>';
		                  		}
		                  		echo '</tr>';
		                		
		                	}
		                  }
		                  ?>
		                
		                
		              </tbody>
		            </table>
			    </div>
			  </div>
		</div>
      </div>

      <hr>

      <div class="footer">
        <p>Developed by Raghunath J. Build Version 1</p>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
    	$(document).ready(function(){
    		//Kept for future use.
    	});
    </script>
  </body>
</html>
<?php
}