'use strict';

var translate = function(x) {
  return x;
};
var show_message = function(message, type) {
  if (!type) {
    type = 'success';
  }
  $('.top-right').notify({
    'type': type,
    message: {
      'text': message
    },
    fadeOut: {
      enabled: true,
      delay: 5000
    }
  }).show();
};

$(document).ready(function() {
  var bar = $('.progress-bar');
  var upload_submit = $('#upload_submit');
  var upload_form = $('#upload_form');

  var explain = $('#explain');
  var dropbox = $('.dropbox');

  // uncomment this to try non-HTML support:
  //window.File = window.FileReader = window.FileList = window.Blob = null;

  var html5 = window.File && window.FileReader && window.FileList && window.Blob;
  $('#wait').hide();

  var handleFileSelect = function(f) {
    if (!html5) {
      return;
    }
    explain.html(translate('Loading document...'));
    var output = '';
    output = translate('Preparing to hash ') + escape(f.name) + ' (' + (f.type || translate('n/a')) + ') - ' + f.size + translate(' bytes, last modified: ') + (f.lastModifiedDate ? f.lastModifiedDate
      .toLocaleDateString() : translate('n/a')) + '';

    var reader = new FileReader();
    reader.onload = function(e) {
      var data = e.target.result;
      bar.width(0 + '%');
      bar.addClass('bar-success');
      explain.html(translate('Now hashing... ') + translate('Initializing'));
      setTimeout(function() {
        CryptoJS.SHA256(data, crypto_callback, crypto_finish);
      }, 200);

    };
    reader.onprogress = function(evt) {
      if (evt.lengthComputable) {
        var w = (((evt.loaded / evt.total) * 100).toFixed(2));
        bar.width(w + '%');
      }
    };
    reader.readAsBinaryString(f);
    show_message(output, 'info');
  };
  if (!html5) {
    explain.html(translate('disclaimer'));
    upload_form.show();
  } else {
    dropbox.show();
    dropbox.filedrop({
      callback: handleFileSelect
    });
    dropbox.click(function() {
      $('#file').click();
    });
  }
  

  var crypto_callback = function(p) {
    var w = ((p * 100).toFixed(0));
    bar.width(w + '%');
    explain.html(translate('Now hashing... ') + (w) + '%');
  };

  var crypto_finish = function(hash) {
    bar.width(100 + '%');
    explain.html(translate('Document hash: ') + hash);
    $('#signature').val(hash);
    //console.log("hash loaded: "+hash);
    //$.post('./api/v1/register/' + hash, onRegisterSuccess);
  };

 $( "#upload_form" ).submit(function( event ) {
  //alert( "Handler for .submit() called." );
  event.preventDefault();
  var signature = $('#signature').val();
  //console.log(name,email,message,signature);
  window.location.href = "details.php?signature="+signature;

//   $.post('./api/v1/verify/' + signature, function(data){
//     console.log(data);
//     $('#wait').remove();
//     $('#description_container').append("<h2>Success</h2>");
//     var items = [];
//       items.push(
//         '<table class="table table-striped table-hover"><thead><tr><th> Data </th><th> Value</th></tr></thead>');
       
//         Object.keys(data).forEach(function(key) {
//           if (key=="long_url" || key=="short_url")
//              items.push('<tr><td>' + key+ '</td><td><a href='+data[key]+' target="_blank">' + data[key]+ '</a></td></tr>');
//           else
//             items.push('<tr><td>' + key+ '</td><td>' + data[key]+ '</td></tr>');
//         });
// items.push('</table>');
      
//         $("#description_container").append(items.join(''));
      
//       // $('<div/>', {
//       //   'class': 'table',
//       //   html: items.join('')
//       // }).appendTo(container);
//   }, 'json');

//  $( "#upload_form" ).trigger('reset');
//  explain.html(translate(''));
//  bar.width('0%');

//  $('#description').remove();
//  $('#description_container').append('<div id="wait"><h2> Generating PoE... Please wait...</h2><br/><img src="img/gears.gif"/></div>');
  
});
 
  document.getElementById('file').addEventListener('change', function(evt) {
    var f = evt.target.files[0];
    handleFileSelect(f);
  }, false);

  // upload form (for non-html5 clients)
  upload_submit.click(function() {
    upload_form.ajaxForm({
      dataType: 'json',
      beforeSubmit: function() {
        var percentVal = '0%';
        bar.removeClass('bar-danger');
        bar.removeClass('bar-warning');
        bar.removeClass('bar-success');
        bar.addClass('bar-info');
        bar.width(percentVal);
      },
      uploadProgress: function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        bar.width(percentVal);
      },
      success: onRegisterSuccess
    });

  });
});
