function addItem(){
  let id = document.querySelectorAll('#items .item').length;
  let html = '<br><div class="item">\
  <input style="display:inline-block; width: 50%;" class="oxinput" type="text" name="items['+id+'][description]" value="" placeholder="Beschreibung*">\
  <input style="display:inline-block; width: 15%;" class="oxinput" type="text" pattern="[0-9]{1,}" name="items['+id+'][amount]" value="" placeholder="Anzahl*">\
  <input style="display:inline-block; width: 10%;" class="oxinput" type="text" name="items['+id+'][amount-type]" value="" placeholder="Stück*">\
  <input style="display:inline-block; width: 20%;" class="oxinput" type="text" pattern="[0-9]{1,}\.[0-9]{2}" name="items['+id+'][price]" value="" placeholder="Preis(13.50)*">\
  </div>';
  $('#items').append(html);
}

function changeStatus(id){
  let html = oxymora.lightboxQuestion('Neuer Status')+lightboxSelect('status', [{value:'0', text:'Eröffnet'},{value:'1', text:'Gestellt'},{value:'2', text:'Bezahlt'}], 'Neuer Status');
  oxymora.showLightbox(html, function(success, lbdata){
    if(success && lbdata['status']){
      $.post('index.php', {'ajax': 'setInvoiceStatus','id':id, 'status':lbdata['status']}, function(){
        location.reload();
      });
    }
  });
}
