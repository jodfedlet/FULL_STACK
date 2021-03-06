<?php
include "../Includes/connect.php";
if(isset($_POST['addproduct'])){
	
	$name = addslashes($_POST['name']);
	
	$img = (empty($_FILES['imgPerfil']['name']))? 'NULL' : "{$_FILES['imgPerfil']['name']}"; 
	
	$quanti = $_POST['Quantity'];
	$price = str_replace(",", ".", $_POST['price']);
	$categorie = ($_POST['categorie'] == '') ? 'NULL' : $_POST['categorie'];
	$disc = str_replace(",", ".", $_POST['discount']);
	$marca = $_POST['marque'];

	$erros = array();

	if(empty($name)){
		$erros[] = "Enter the product name";
	}

	if(!is_numeric($price)){
		$erros[] = "Valor da locação inválido";
		$price = 0;
	}

	if(!is_numeric($disc)){
		$erros[] = "Valor do desconto inválido";
		$discount = 0;
	}
	if(empty($marca)){
		$erros[] = "Enter the Brand";
	}

	/*if(($price - $disc) <= 0){
		$erros[] = "O valor final deve ser maior do que zero";
	}*/

	
if($img <> 'NULL'){
		$from = "../img/".$_FILES['imgPerfil']['name'];
		if(!move_uploaded_file($_FILES['imgPerfil']['tmp_name'], $from)){
			$erros[] = "Falha no upload do arquivo";
		}
	}





	
	

	if (count($erros) == 0){ // nenhum erro encontrado
	 	echo $sql = " INSERT INTO product (idCtg,namep,price,marcap,img,quant,disc) VALUES ($categorie,'$name',$price,'$marca','$img',$quanti,$disc)";
		$resultado = mysqli_query($connect, $sql);
		if($resultado){
			$mensagem = "<p class='alert alert-success text-center'>O produto <strong>$name</strong> foi inserido com sucesso <p>";
		}
		else{
			$mensagem = "Erro. O produto não pôde ser cadastrado. ";
			$mensagem .= mysqli_error($connect); // para debug
		}
	} // count erros


}
else{ // carrega dados atuais
		$sql = "select p.idp,p.namep, p.price, p.marcap, p.img,p.quant,p.disc, c.cName from product as p left join categorie as c on p.idCtg = c.idCtg where p.idp = {$_GET['id']}";
		$resultado = mysqli_query($connect, $sql);
		$dados = mysqli_fetch_array($resultado);
	
}

include "../Includes/functions.php";

include 'adm-head.php';

?>

				<div class="row"  id="form-boxadd">


					<div class="col-sm-12" >
					  <form action="" method="post" enctype="multipart/form-data">
    
               
          				<h4 class="text-center cre"><em>Add product</em></h4>
		          					<?php
						if (isset($mensagem)){
							echo "<p>$mensagem</p>";
						}
						else{ // carrega form
							if(isset($erros)){
								echo "<ul>";
								foreach ($erros as $erro){
									echo "<li style='color: red;'>$erro</li>";
								}
								echo "</ul>";
							}
						?>
					    <div class="col-sm-5" >
						    <div class="form-group">
			                  <label for="pname">Product name:</label>
			                  <input type="text" class="form-control" id="pname" name="name" value="<?=isset($_POST['name']) ? $_POST['name'] : $dados['namep'];?>">
			              	</div>

			              	<div class="form-item">
								<label for="fabricante" class="label-alinhado">Cateorie:</label>
									<select name="categorie" id="categorie">
										<option value="">Select a categorie</option>
											<?php										
												$sql = "select * from categorie order by cName";
												$res = mysqli_query($connect, $sql);
												
												while ($cat = mysqli_fetch_array($res)){	
													echo "<option value='{$cat['idCtg']}'";
													if(isset($_POST['categorie'])){
														if($_POST['categorie'] == $cat['idCtg'])  
														  echo " selected";	
													}	  
													echo ">{$cat['cName']}</option>";
												}

											?>	
									</select>
							</div>			

							<div class="form-group">
			                  <label for="pname">Quantity:</label>
			                  <input type="text" class="form-control" id="pname" name="Quantity" value="<?=isset($_POST['Quantity']) ? $_POST['Quantity'] :$dados['quant'];?>">
			              	</div>
						</div>


		              <div class="col-sm-5" >
		              	 <div class="form-group">
			                  <label for="price">Brand:</label>
			                  <input type="text" class="form-control" id="marq" name="marque" value="<?=isset($_POST['marque'])? $_POST['marque']:$dados['marcap'];?>">
		             	 </div>

			              
		              	<div>
							<label for="arquivo" class="label-alinhado">Select an image</label>
							<input type="file" id="img" name="imgPerfil" >
						    <div class="img">								  
								<figure>
									<img src="<?=showImg($dados['img']);?>" alt="<?=$dados['name'];?>" width="100" height="100">							
								</figure>							
						    </div> 								
						</div>

			              <div class="form-group pull-right">
			                  <label for="price">Discount:</label>
			                  <input type="number" class="form-control " name="discount" style="max-width:70%;"  value="<?=isset($_POST['discount'])? $_POST['discount']:$dados['disc'];?>">
		             	 </div>
		              </div>
  						
  						<div class="col-sm-2" >
		              	 	<div class="form-group">
			                  <label for="pname">Price:</label>
			                  <input type="number" class="form-control" id="price"  name="price"  value="<?=isset($_POST['price'])? $_POST['price']:$dados['price'];?>">
			              	</div>
		              </div>
					              
			         
			                

			             <div class="col-sm-12" >
			                    <input type="submit"  type="button" name="addproduct" class="btn btn-success pull-right" value="Confirm edit">
			              </div>
			            
			         
					  
					    </form>
					   <?php
				} // fecha else
			?>
				</div>	
			</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="js/script.js"></script>
</body>

</html>