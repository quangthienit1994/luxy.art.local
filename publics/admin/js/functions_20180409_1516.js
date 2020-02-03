toastr.options = {
  "closeButton": true,
  "debug": true,
  "newestOnTop": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "preventDuplicates": false,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "3000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
};

var confirmSwal = {
  title: "Are you sure?",
  text: "You will not be able to recover!",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "red",
  confirmButtonText: "Yes",
  cancelButtonText: "Cancel",
  showLoaderOnConfirm: true,
  closeOnConfirm: false,
  closeOnCancel: true
};

function alertSuccess(message = '', title = '') {
    if(title == '') title = '成功 !';
    toastr.success(message, title);
}

function alertError(message = '', title = '') {
    if(title == '') title = 'エラー !';
    toastr.error(message, title);
}

function alertWarning(message = '', title = '') {
    if(title == '') title = '警告 !';
    toastr.warning(message, title);
}

function alertInfo(message = '', title = '') {
    if(title == '') title = '情報 !';
    toastr.info(message, title);
}

function hiddenShow(e, time = 0) {
  var show = $(e).data('show');
  var hidden = $(e).data('hidden');
  $(hidden).hide(time);
  $(show).removeClass('hidden');
  $(show).show(time);
  
}

function v_required(e) {
    var check = false;
    $(e).find('.v-required').each(function(index, elem){
      if($(elem).val() == '') {
        var note = $(elem).data('required-message');
        if(note == undefined) note = "This field don't empty !";
        alertError(note);
        if(check == false) check = true;
      }
    });
    return check;
}

function loadCkeditor(){
  for ( instance in CKEDITOR.instances )
       CKEDITOR.instances[instance].updateElement();
}


function submitAjax(e, attribute = []) {
    if(v_required(e)) return false;
    
    var confirm = attribute['confirm'];
    if(confirm != undefined) {
      swal(confirmSwal, function(isConfirm){
          if(isConfirm) {
            resultAjax(e, attribute);
            setTimeout(function(){
              swal.close();
            },500);
          }
      });
    }else {    
      resultAjax(e, attribute);
    }
    return false;
}


function resultAjax(e, attribute = []){
  loadCkeditor();
  $('.loader').removeClass('hidden');

  var url = attribute['url'];
  var data = attribute['data'];
  var load = attribute['load'];
  var redirect = attribute['redirect'];
  var reset = attribute['reset'];
  var call = attribute['call'];
  var type = $(e).attr('method');

  if(url == undefined)  url = $(e).attr('action');

  if(type == undefined || type == '' ) type = "POST";

  if(data == undefined){
    if(type == 'get' || type == "GET") {
      data = $(e).serialize();
    }else data = new FormData(e);
  }

  
  
  
  var a = ajax(url, type, data);

  a.then(function(result){
    // data
    if(result.load != undefined) load = result.load;
    if(result.redirect != undefined) redirect = result.redirect;
    if(reset == true) e.reset();
    if(call != undefined && call != '') call.call();

    if(result.warning != undefined){
      alertWarning(result.warning);  
    } 

    if(result.info != undefined){
      alertInfo(result.info);  
    } 

    

    if(result == true || result.success != undefined) {
      alertSuccess(result.success);  
      if(load != undefined) {
        setTimeout(function(){
          if(redirect != undefined) {
            location.href = redirect;
          }else{
            location.reload();
          }
        }, load);
      } 
    }

    if(result.error != undefined) {
      alertError(result.error); 
      if(result.data) {
        var note = result.data;
        $('.note').addClass('hidden');  
        $('.note_bg').removeClass('note_active_bg');
        for(var index in note) { 
            $("."+index).removeClass('hidden');
            $("."+index).html(note[index]);
            $("."+index+"_bg").addClass('note_active_bg');
        }
      }
    }
    
    $('.loader').addClass('hidden');
  })
  .fail(function(e){
    alertError("Server error !");
    $('.loader').addClass('hidden');
  });

  return a;
}


// Ajax
function ajax(url, type, data){
  return $.ajax({
    url: url,
    type: type,
    processData: false,
    contentType: false,
    dataType: 'json',
    data: data
  });
}

// onsubmit
function submitForm(e){
  $(e).parents('form').submit();
}

// onSlug
function onSlug(e, slug){
  $.get(url_slug, {'name':$(e).val()}, function(data){
      $(slug).val(data);
  });
}

// Show popup modal
function popupModal(idPopup, animation) {
    $.magnificPopup.open({
        removalDelay: 500, //delay removal by X to allow out-animation,
        items: {
            src: idPopup
        },
        // overflowY: 'hidden', // 
        callbacks: {
            beforeOpen: function(e) { 
                this.st.mainClass = animation;
            }
        },
        midClick: true // allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source.
    });
}
