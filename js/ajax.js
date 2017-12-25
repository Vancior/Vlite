function default_callback(err, data){
    if(err){
        console.log(err);
    }else{ 
        console.log(data);
    }
}
function XHRRequest(method, target, data, callback){
    var xhr = new XMLHttpRequest();
    xhr.open(method,target,true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if(xhr.status == 200){
                callback(undefined, xhr.responseText);
            }else{
                callback(xhr, undefined);
            }
        }
    };
    xhr.send(data)
}
function JQRequest(method, target, data, callback){
    $.ajax({
        async: true,
        method: method,
        url: target,
        data: data,
        complete: (xhr) => {
            if (xhr.readyState == 4) {
                if(xhr.status == 200){
                    callback(undefined, xhr.responseText);
                }else{
                    callback(xhr, undefined);
                }
            }
        }
    });
}
var request = JQRequest;
function AjaxCreater(method){
    if (typeof(method) === 'string'){
        method = method.toUpperCase()
    }else{
        throw Error('argument must be string');        
    }
    var acceptable_method = ['GET', 'POST', 'PUT', 'DELETE'];
    if (acceptable_method.includes(method)){
        return function (target, data, callback){
            callback = callback || default_callback;
            data = data || {};
            request(method, target, data, callback);
        };
    }else{
        throw Error("["+ method +"] is not an acceptable method!");
    }
}
const AjaxGet = AjaxCreater('GET');
const AjaxPost = AjaxCreater('POST');
const AjaxPut = AjaxCreater('Put');
const AjaxDelete = AjaxCreater('Delete');