<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>


<form name="f1"> 
Nombre: <input type="text" name="nombre"> 
<br> 
<input type="checkbox" name="ch1"> Opcion 1 
<br> 
<input type="checkbox" name="ch2"> Opcion 2 
<br> 
<input type="checkbox" name="ch3"> Opcion 3 
<br> 
<input type="checkbox" name="ch4"> Opcion 4 
<br> 
//Otro campo de formulario: 
<select name=otro> 
<option value="1">Seleccion 1 
<option value="2">Seleccion 2 
</select> 
<br> 
<input type="submit"> 
<br> 
<br> 
<a href="javascript:seleccionar_todo()">Marcar todos</a> | 

</form>

</body>
</html>

<script type="text/javascript">
function seleccionar_todo(){ 
	var contador=2;
   for (i=0;i<=contador;i++) 
      if(document.f1.elements[i].type == "checkbox")	
         document.f1.elements[i].checked=1 
} 

</script>