function dg_send(form, datagridName, type, url)
{
    var oForm = $(form);
    
    switch(type)
    {
        case 'search':
            new Ajax.Updater(datagridName, url, {asynchronous:true, evalScripts:true, method:'get', parameters:oForm.serialize(this)}); return false;
            break;
            
        case 'action':
            
            new Ajax.Updater(datagridName, $(datagridName + '_select').options[$(datagridName + '_select').selectedIndex].value, {asynchronous:true, evalScripts:true, parameters:oForm.serialize(this)}); return false;
            break;
    }
}

function dg_keydown(form, datagridName, type, url, e)
{
    if(e.keyCode == 13)
    {
        dg_send(form, datagridName, type, url);
    }
    
    return false;
}