aujour=new Date(); // Pour générer les années
mois=new Array("Janvier","F&eacute;vrier","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t",
"Septembre","Octobre","Novembre","D&eacute;cembre");

// Création des select pour une date
// Denis Blomme - DB77
function creerselect(label,no,plugin){
//box=eval("document.cb-"+no);
box=document.getElementById("cb-"+no);
//if(box.checked==false){
	// Création de la liste déroulante des numéros des jours
	document.write("<select name='"+plugin+"[dd-"+label+'-'+no+"]'>");
	for(i=1;i<=31;i++){
		document.write("<option value="+i);
		if(aujour.getDate()==i){document.write(" selected");}
		document.write(">"+i+"</option>");
	}
	document.write("</select>");
	// Création de la liste déroulante des libellés des mois
	document.write("<select name='"+plugin+"[mm-"+label+'-'+no+"]'>");
	for(i=0;i<=11;i++){
		document.write("<option value="+(i+1));
		if(aujour.getMonth()==i){document.write(" selected");}
		document.write(">"+mois[i]+"</option>");
	}
	document.write("</select>");
	// Création de la liste déroulante des 10 années avant et après
	annee=aujour.getYear();if(annee<1900){annee=annee+1900;}
	document.write("<select name='"+plugin+"[yy-"+label+'-'+no+"]'>");
	for(i=-10;i<=10;i++){
		document.write("<option value="+(annee-i));
		if(i==0){document.write(" selected");}
		document.write(">"+(annee-i)+"</option>");
	}
//}else{document.write("-");}
}


//MODIFIE PAR THIBAUT début


function safemail(prefix, suffix, caption) {
   if (caption == undefined) caption = prefix + "@" + suffix;
   document.write("<a class=\"email\" href=\"mailto:" + prefix + "@" + suffix + "\">" + caption + "</a>");
}


//MODIFIE PAR THIBAUT fin


/*
Strip whitespace from the beginning and end of a string
Input  : a string
Output : the trimmed string
*/
function trim(str)
{
	return str.replace(/^\s+|\s+$/g,'');
}

/*
Check if a string is in valid email format. 
Input  : the string to check
Output : true if the string is a valid email address, false otherwise.
*/
function isEmail(str)
{
	var regex = /^[-_.a-z0-9]+@(([-a-z0-9]+\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i;
	return regex.test(str);
}

function checkDates(){
	if(document.getElementById("tx_casreservation_pimanage[change_0]").checked)return true;
	if(document.getElementById("tx_casreservation_pimanage[change_1]").checked)return true;
	if(document.getElementById("tx_casreservation_pimanage[change_2]").checked)return confirm('Avez-vous inscrit correctement les dates de facturation ? \nCliquez sur ok si vous avez fait cela.');
	if(document.getElementById("tx_casreservation_pimanage[change_3]").checked)return confirm('Avez-vous inscrit correctement les dates de paiement ainsi que le montant ? \nCliquez sur ok si vous avez fait cela.');
}

/* 2 fcts Used to show text when hovering*/
function ShowText(id) {
document.getElementById(id).style.display = 'block';
}

function HideText(id) {
document.getElementById(id).style.display = 'none';
}
