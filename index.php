<?php
include("fun.inc.php");
$file = fopen(CSV_FILE, 'r');
$data = array();
while($row = fgetcsv($file)) {
   $data[] = $row;
}
//print_r($data);
$total_lines = count($data);
//Check or set header
$header = true;
if(!empty($_GET['header'])){
	$hd = $_GET['header'];
	switch($header){
		case "true":
		$header = true;
		break;
		case "false":
			$header = false;
		break;
	}
}

if($header){
	$head[] = $data[0];
}

//get table column
$table_name = DB_TABLE;

$col = get_table_structure($table_name);
$table_column_count = count($col);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Punch - App to update data from CSV to MySQL</title>
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
			    <li class="active"><a href="#tab1" data-toggle="tab">Action</a></li>
			    <li><a href="#tab2" data-toggle="tab">Sample Data</a></li>
			     <li><a href="#tab3" data-toggle="tab">CSV Info</a></li>
			  </ul>
			  <div class="tab-content">
			    <div class="tab-pane active" id="tab1">
			      <form method="post" action="process.php">
					  <fieldset>
					    <legend>Search Key</legend>
					    <label>Select Column</label>
					    <?php
					    	$h_len = count($head[0]);
		                	$head_d = $head[0];
					    ?>
					    <select class="columns" name="primarydb[]">
						    	<?php
						    	foreach($col as $c){
						    			echo '<option value="'.$c['Field'].'">'.$c['Field'].'</option>';
			                	}
						    	?>
						</select> from
					    <select class="primarykeys" name="primarykey[]">
					    	<?php
					    	for($hc=0;$hc<$h_len;$hc++){
		                		echo '<option value="'.$hc.'">'.$head_d[$hc].'</option>';
		                	}
					    	?>
					    </select>
					    <br/><!--<button class="btn btn-mini btn-primary" type="button">add new column</button>--><br/><br/>
					    <legend>Update keys</legend>
					    <label>From CSV to DB (primary keys are ignored)</label>
					    <p id="newcolumns">
						    <select id="set1" class="columns" name="columns_a[]">
						    	<?php
						    	for($hc=0;$hc<$h_len;$hc++){
			                		echo '<option value="'.$hc.'">'.$head_d[$hc].'</option>';
			                	}
						    	?>
						    </select> to
						    <select id="set2" class="columns" name="columns_b[]">
						    	<?php
						    	$cn = 0;
						    	$ignore = 0;
						    	foreach($col as $c){
						    		if($c['Key']!="PRI"){
						    			echo '<option value="'.$c['Field'].'">'.$c['Field'].'</option>';
						    		}else{
						    			$ignore = $ignore + 1;
						    		}
			                	}
						    	?>
						    </select>
						</p>
						<button id="extendcolumn" class="btn btn-mini btn-primary" type="button">add new column</button><br/><br/>
					    <br/><button type="submit" class="btn">Submit</button>
					  </fieldset>
					</form>

			    </div>
			    <div class="tab-pane" id="tab2">
			      <table class="table table-bordered">
		              <thead>
		                <tr>
		                <?php
		                	
		                	for($hc=0;$hc<$h_len;$hc++){
		                		echo '<th>'.$head_d[$hc].'</th>';
		                	}
		                ?>
		                </tr>
		              </thead>
		              <tbody>
		                <tr>
		                  <td rowspan="2">1</td>
		                  <td>Mark</td>
		                  <td>Otto</td>
		                  <td>@mdo</td>
		                </tr>
		                
		              </tbody>
		            </table>
			    </div>
			    <div class="tab-pane" id="tab3">
			      <pre>Total Lines read from CSV: <?php echo $total_lines; ?></pre>
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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
    	$(document).ready(function(){
    		var current_table_count = 1;
    		var table_column_count = <?php echo $table_column_count; ?>;
    		var total_columns = table_column_count -  <?php echo $ignore; ?>;
    		$("#extendcolumn").click(function(e){
    			e.preventDefault();
    			if(current_table_count != total_columns){
    				current_table_count = current_table_count + 1;
	    			var br = '<br/>';
					var group = '#group'+current_table_count;
					$("#newcolumns").append('<div id="group'+current_table_count+'"></div>');
					$(group).append(br);
	    			$('select#set1').clone().attr("id","le"+current_table_count).appendTo(group);
	    			$(group).append(" to ");
	    			$('select#set2').clone().attr("id","re"+current_table_count).appendTo(group);
	    			$(group).append(' <button class="btn btn-mini btn-danger rem" data-removal="group'+current_table_count+'" type="button">remove column</button>');
    			}else{
    				$("#extendcolumn").hide();
    			}
    		});
			
			$(".rem").live("click",function(e){
				e.preventDefault();
				var data_remove = $(this).attr("data-removal");
				$("#"+data_remove).remove();
				current_table_count--;
				if(current_table_count != total_columns){
					$("#extendcolumn").show();
				}
			});
    	});
    </script>
  </body>
</html>