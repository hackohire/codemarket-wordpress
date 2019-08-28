/* arkemoticons v 1.0
 * (c) Alexander Karataev 10/2014
 * ddw2@yandex.ru
 */

tinymce.PluginManager.add('arkkbabe', function(editor, url) {
var arkkbabe = [
["01", "02", "03", "04", "05", "06"],
["07", "08", "09", "10", "11", "12"],
["13", "14", "15", "16", "17", "18"],
["19", "20", "21", "22", "23", "24"],
["25", "26", "27", "28", "29", "30"]
];

function getHtml() {
var arkkbabeHtml;

arkkbabeHtml = '<table role="list" class="mce-grid" style="width:350px !important; height:270px !important;">';

tinymce.each(arkkbabe, function(row) {
arkkbabeHtml += '<tr>';

tinymce.each(row, function(icon) {
var arkkbUrl = url + '/img/' + icon + '.gif';

arkkbabeHtml += '<td><a href="#" data-mce-url="' + arkkbUrl + '" data-mce-alt="' + icon + '" tabindex="-1" ' +
'role="option" aria-label="' + icon + '"><img src="' +
arkkbUrl + '" role="presentation" /></a></td>';
});

arkkbabeHtml += '</tr>';
});

arkkbabeHtml += '</table>';

return arkkbabeHtml;
}

editor.addButton('arkkbabe', {
type: 'panelbutton',
panel: {
role: 'application',
autohide: true,
html: getHtml,
onclick: function(e) {
var linkElm = editor.dom.getParent(e.target, 'a');

if (linkElm) {
editor.insertContent(
'<img src="' + linkElm.getAttribute('data-mce-url') + '" alt="' + linkElm.getAttribute('data-mce-alt') + '" />'
);

//this.hide();
}
}
},
tooltip: 'Smile k-babe',
image: url + '/img/smkbabe.png'
});
});

