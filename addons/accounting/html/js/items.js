function addItem(){
  let id = document.querySelectorAll('#items .item').length;
  let html = '<br><div class="item">\
  <input type="input" name="items['+id+'][\'description\']" value="" placeholder="Beschreibung*">\
  <input type="input pattern="[0-9]{1,}" name="items['+id+'][\'amount\']" value="" placeholder="Anzahl*">\
  <input type="input" pattern="[0-9]{1,}\.[0-9]{2}" name="items['+id+'][\'price\']" value="" placeholder="Preis(13.50)*">\
  </div>';
  $('#items').append(html);
}
