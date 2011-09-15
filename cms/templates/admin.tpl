
<!DOCTYPE html> 
<html> 
	<head> 
	<title>Page Title</title>
    	
    <meta charset="utf-8" /> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.3.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0b3/jquery.mobile-1.0b3.min.js"></script>
</head> 

<body> 

<!-- Start of first page: #one -->
<div data-role="page" id="one">

	<div data-role="header">
		<h1>Список классов</h1>
        <a href="/logout/" data-ajax="false" class="ui-btn-right" data-icon="delete">Выйти</a>
	</div><!-- /header -->

	<div data-role="content">	
		<h2>Тут будет таблица со списком классов</h2>
        
        <table id="list5"></table>
        <div id="pager5"></div>		


		<h3>Примеры контролов:</h3>
        <p>
				<select name="select-choice-a" id="select-choice-a" data-icon="gear" data-native-menu="false" data-inline="true"> 
					<option>Действия</option> 
					<option value="view">Просмотр</option> 
					<option value="change">Изменить</option> 
					<option value="delete">Удалить</option> 
					<option value="onemore">Еще метод</option> 
				</select>
        
        </p>        
		<p><a href="#two" data-role="button">Показать вторую страницу</a></p>	
		<p><a href="#popup"data-role="button" data-rel="dialog" data-transition="pop">Пример выбора вьюхи</a></p>
	</div><!-- /content -->

</div><!-- /page one -->


<!-- Start of second page: #two -->
<div data-role="page" id="two">

	<div data-role="header">
		<h1>Two</h1>
        <a href="/logout/" data-ajax="false" class="ui-btn-right" data-icon="delete">Выйти</a>
	</div><!-- /header -->

	<div data-role="content">	
		<h2>Two</h2>
		<p>I have an id of "two" on my page container. I'm the second page container in this multi-page template.</p>	
		<p>Notice that the theme is different for this page because we've added a few <code>data-theme</code> swatch assigments here to show off how flexible it is. You can add any content or widget to these pages, but we're keeping these simple.</p>	
		<p><a href="#one" data-direction="reverse" data-role="button" data-theme="b">Назад к первой странице</a></p>	
		
	</div><!-- /content -->

</div><!-- /page two -->


<!-- Start of third page: #popup -->
<div data-role="page" id="popup">

	<div data-role="header">
		<h1>Режимы просмотра</h1>
	</div><!-- /header -->

	<div data-role="content">	
		<h2>Выберите режим просмотра</h2>
		<p>
            <div data-role="controlgroup">
            <a href="#two" data-role="button">Вьюха 1</a>
            <a href="#two" data-role="button">Вьюха 2</a>
            <a href="#two" data-role="button">Вьюха 3</a>
            </div>
        </p>		
		<p><a href="#one" data-rel="back" data-role="button" data-inline="true" data-icon="back">Вернуться</a></p>	
	</div><!-- /content -->

</div><!-- /page popup -->
<script>
$(document).ready( function() {
    
    $('#select-choice-a').change(function(){
        var myselect = $("select#select-choice-a");
        alert('Вызывается JSON-RPC метод '+myselect.val());
        myselect[0].selectedIndex = 0;
        myselect.selectmenu("refresh"); 
    });
    
    jQuery("#list5").jqGrid({        
       	url:'/server.php?q=2',
    	datatype: "json",
       	colNames:['Inv No','Date', 'Client', 'Amount','Tax','Total','Notes'],
       	colModel:[
       		{name:'id',index:'id', width:55},
       		{name:'invdate',index:'invdate', width:90},
       		{name:'name',index:'name', width:100},
       		{name:'amount',index:'amount', width:80, align:"right"},
       		{name:'tax',index:'tax', width:80, align:"right"},		
       		{name:'total',index:'total', width:80,align:"right"},		
       		{name:'note',index:'note', width:150, sortable:false}		
       	],
       	rowNum:10,
       	rowList:[10,20,30],
       	pager: '#pager5',
       	sortname: 'id',
        viewrecords: true,
        sortorder: "desc",
        caption:"Simple data manipulation",
        editurl:"someurl.php"
    }).navGrid("#pager5",{edit:false,add:false,del:false});    
    
});


</script>

</body>
</html>