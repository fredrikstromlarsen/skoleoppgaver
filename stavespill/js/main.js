// Check if every input has a value and enable 
// submit button if statement is true
$('form.task').onchange = () => {
	for (var i = 0; i < $$('input.char').length; i++)
        if ($$('input.char')[i] == '') { $(input.answer).disabled = false; return false;}
    return $(input.answer).disabled = false;
};
