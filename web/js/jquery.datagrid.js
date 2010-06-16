function dg_send(form, datagridName, type, url, freeze_after)
{
	
	
    var oForm = $("form#"+form);
    
    switch(type)
    {
        case 'search':
        	dg_hide_show(datagridName);
        	$.ajax({
        		url: url,
        		global: false,
        		type: 'POST',
        		data: (oForm.serialize()),
        		success: function(msg){
        			$('div#'+datagridName).empty().append(msg);
        		 	$('div#loader-' + datagridName).hide();	
        		 	if(freeze_after){
        		 		freezePanes(datagridName);
        		 	}
        		 }
        	});
            break;
        
        case 'reset':
        	oForm=document.getElementById(form);
        	el=oForm.elements;
        
        	
        	for(i in el){
        		
        			el[i].value='';
        			
        		
        	}
        	url+='/reset/1';
        	dg_send(form, datagridName, 'search', url, freeze_after);
            break;
           
        case 'action':
        	dg_hide_show(datagridName);
        	$.ajax({
        		url: $('#'+datagridName + '_select').val(),
        		global: false,
        		type: 'POST',
        		data: (oForm.serialize()),
        		success: function(msg){
        			$('div#'+datagridName).empty().append(msg);
        		 	$('div#loader-' + datagridName).hide();
        		 	if(freeze_after){
        		 		freezePanes(datagridName);
        		 	}	
        		 }
        	});
           
            
            break;
    }
}

function dg_check_all(chk){
    var checked_status = chk.checked;
    $(chk).parent().parent().parent().find("input.gridline_chk[type='checkbox']").attr('checked',checked_status);
}
function dg_keydown(form, datagridName, type, url, e, freeze_after)
{
    if(e.keyCode == 13)
    {
        dg_send(form, datagridName, type, url, freeze_after);
    }
    
    return false;
}

function dg_hide_show(name)
{    
    if($('div#loader-' + name))
    {
        $('div#loader-' + name).show();
    }
}

function function_exists (function_name) {
    if (typeof function_name == 'string'){
        return (typeof this.window[function_name] == 'function');
    } else{
        return (function_name instanceof Function);
    }
}