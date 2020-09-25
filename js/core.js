function getVar(key, default_) {
	if (default_==null) default_=0;
	key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regex = new RegExp("[\\?&#]"+key+"=([^&#]*)");
	var qs = regex.exec(window.location.href);
	if(qs == null) return default_; else return qs[1];
}

/* ============= Gestion des colonne stick (et du menu pour celle de gauche) ================ */
var menuTop;
var speed = 900;
function initSummary() {
	// menuTop=$("#ei_tpl_head").height()+$("#ei_tpl_menuPrincipal").height()+3-10; //
	menuTop = parseInt( $("#ei_tpl_fullsite").css("padding-top") ) - 10;
	if ( $("aside > nav.summary").length ) {
		// var scrolled=$("#ei_tpl_content").offset().top-$("#ei_tpl_head").height()-$("#ei_tpl_menuPrincipal").height()+3-4; //+3 ==> � cause de l'ombre // -4 ==> le chevauchement entre header et nav
//		var scrolledStop=$(".main > article").offset().top+$(".main > article").height();
//		var menuLeft=$("#ei_tpl_content").offset().left + parseInt($("#ei_tpl_content").css("padding-left"));
		
		// Init sections
		var sections = [];
		if ( $("aside > nav.summary").length ) { // Si j'ai un menu <nav> dans mon �l�ment sticky
			var speed = 900;
			$("article section").each(function(index) {
				sectionOffsetTop = Math.floor($(this).offset().top - menuTop);
				sections.push( sectionOffsetTop );
				$("nav.summary a[href='#" + $(this).attr("id") + "']").click( function() {
					$('html, body').animate( { scrollTop: sections[index] }, speed );
					return false;
				});
			});
		}
		
//		$("html").menu_scrollSticky(scrolled,scrolledStop,menuTop,menuLeft,sections);
		// menu sticky
//		$(window).scroll(function(){
//			$("html").menu_scrollSticky(scrolled,scrolledStop,menuTop,menuLeft,sections);
//		});
		$(window).resize(function(){
//			menuLeft=$("#ei_tpl_content").offset().left;
//			if($("html").hasClass('menuSticky')){
//				if ( $(".hasRightAside").length ) {
//					$("aside.sticky").css("right",menuLeft);
//				} else {
//					$("aside.sticky").css("left",menuLeft);
//				}
//			}
		});
	}
}
$(document).ready(initSummary);

/* =================== Scroller jusqu'� une section/un �l�ment ============================ */
function Scroll2ID(id) {
	var OffsetTop = Math.floor( $("#"+id).offset().top - menuTop );
	$('html, body').animate( { scrollTop: OffsetTop }, 900 );
}
function Scroll2Hash() {

	//d�calage li� au header sticky
	var stickyHeight = 0;
	$("*[data-sticky]").each(function(){
		stickyHeight += $(this).outerHeight();
	});
	stickyHeight += $(".ei_header").outerHeight();
	stickyHeight += $("#ei_tpl_menuPrincipal").outerHeight();
//console.log("stickyHeight: "+stickyHeight);

	//smoothscroll si ancre dans l'URL
	var urlHash = window.location.href.split("#")[1];

	//urlHash = ( (urlHash==undefined) && (getVar("hash")!=0) )?( getVar("hash") ):undefined; FX: r�-ecriture de cette ligne ci dessous, cette ligne empechait la r�cup�ration d'ancres. PS: �viter ce type d'�criture peu lisible est propice aux erreurs
	if((urlHash == undefined || urlHash == "") && getVar("hash") != 0)
		urlHash=getVar("hash");

	if ( $( "#"+urlHash ).length && urlHash.substr(0,2)!="DL" ) {
//console.log("urlHash: "+urlHash);
		var OffsetTop = Math.floor( $("#"+urlHash).offset().top - stickyHeight );
		$('html, body').animate( { scrollTop: OffsetTop }, 900 );
	}
}
$(document).ready(Scroll2Hash);

/*==========================================================================
5.  Tableaux responsives
========================================================================== */
function responsiveEntries( table ) {
// ajout d'un identifiant au tableau alternatif
	var tableId=(table.attr("id")!=undefined || table.attr("id")!="")?table.attr("id"):"rwd";
//console.log($("[id^='"+tableId+"-alt-']").length);
//et test de  la non-existantce dans le dom car la fonction se lance plusiiuers fois
if($("[id^='"+tableId+"-alt-']").length==0){
	cols = table.find("thead th:not([colspan])");
	linesTH = table.find("tbody th");
	linesTD = table.find("tbody td");
	linesTDwidthColspan = [];
	for (var c=0 ; c<linesTD.length ; c++) {
		linesTDwidthColspan.push(linesTD[c]);
		if (linesTD[c].colSpan>1) {
			colspan = linesTD[c].colSpan-1;
			for (var cs=0 ; cs<colspan ; cs++) {
				linesTDwidthColspan.push(linesTD[c]);
			}
		}
	}
	newTables = "";
	for (var t=0 ; t<cols.length ; t++) {
		newTables += '<table id="'+tableId+'-alt-'+t+'" class="one-entry border RWD-M-alt">';
		newTables += '<caption>' + cols[t].innerHTML + '</caption>';
		newTables += '<tbody>';
		for (var l=0 ; l<linesTH.length ; l++) {
//console.log(c+" - "+linesTDwidthColspan[t+(l*cols.length)].className);
			attrClass="";
			nameClass=linesTDwidthColspan[t+(l*cols.length)].className;
			if(nameClass!=''){attrClass=" class='"+nameClass+"'"};
			newTables += '<tr>';
			newTables += '<th scope="row">' + linesTH[l].innerHTML + '</th>';
			newTables += '<td'+attrClass+'>' + linesTDwidthColspan[t+(l*cols.length)].innerHTML + '</td>';
			newTables += '</tr>';
		}
		newTables += '</tbody>';
		newTables += '</table>';
	}
//	table.hide();
	table.after(newTables);
}
}

function responsiveLists( table ) {

	lines = table.find("tbody tr");
	linesTH = table.find("thead th");
	linesTD = table.find("tbody td");
	colspan = 1;
	colspanVal = "";
	colspanCount = 0;
	newTables = "";
	for (var t=0 ; t<lines.length ; t++) {
		if ( linesTD[ (t*linesTH.length)-colspanCount ].className == "group") {
			newTables += '<h3 class="RWD-M-alt">' + linesTD[ (t*linesTH.length)-colspanCount ].innerHTML + '</h3>';
			colspanCount += (linesTH.length-1);
		} else {
			if ( lines[t].id ) {
				newTables += '<table class="one-entry border RWD-M-alt" id="ALT-' + lines[t].id + '">';
			} else {
				newTables += '<table class="one-entry border RWD-M-alt">';
			}
			newTables += '<caption>' + linesTD[ (t*linesTH.length)-colspanCount ].innerHTML + '</caption>';
			newTables += '<tbody>';
			for (var l=1 ; l<linesTH.length ; l++) {
				newTables += '<tr>';
				newTables += '<th scope="row">' + linesTH[l].innerHTML + '</th>';
				if ( colspan>1 ) {
					newTables += '<td>' + colspanVal + '</td>';
					colspan--;
				} else {
					newTables += '<td>' + linesTD[ (l+(t*linesTH.length))-colspanCount ].innerHTML.replace("#","#ALT-") + '</td>';
					if ( linesTD[ (l+(t*linesTH.length))-colspanCount ].colSpan>1) {
						colspan = linesTD[ (l+(t*linesTH.length))-colspanCount ].colSpan;
						colspanVal = linesTD[ (l+(t*linesTH.length))-colspanCount ].innerHTML.replace("#","#ALT-");
						colspanCount += (colspan-1);
					}
				}
				newTables += '</tr>';
			}
			newTables += '</tbody>';
			newTables += '</table>';
		}
	}
	table.after(newTables);

}


function initResponsiveTable(e) {
	$("table.two-entry.RWD-M").each(function(){
		responsiveEntries( $(this) );
	});			
	$("table.list.RWD-M").each(function(){
		responsiveLists( $(this) );
	});			
}
$(document).ready(initResponsiveTable);