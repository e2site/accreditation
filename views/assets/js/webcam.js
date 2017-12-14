/**
 * Created by prosto on 11.12.2017.
 */


var WebCam = function(paramObject){
    var defaultParams = {
        canvas: null,
        video: null,
        width:800,
        height:600,
        btnUpload: null,
        btnTakePhoto:null,
        inputId:null,
        btnReTakePhoto:null,
        fncTakePhoto:null,
        fncReTakePhoto:null
    };

    var finalParams = defaultParams;
    for (var key in paramObject) {
        if (paramObject.hasOwnProperty(key)) {
            if (paramObject[key] !== undefined) {
                finalParams[key] = paramObject[key];
            }
        }
    }

    videoObj = {video:true};
    url     = window.URL || window.webkitURL;
    context = finalParams.canvas.getContext("2d");


    finalParams.btnReTakePhoto.style.display="none";
    finalParams.canvas.style.display = "none";

    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
    if(navigator.getUserMedia) {
        console.log("WEBCAM SUPPORTED");
        navigator.getUserMedia(
            videoObj,
            function(stream){
                finalParams.video.src = url.createObjectURL(stream);
                finalParams.video.play();
            },
            function(error){
                alert("Ошибка при попытке инициализировать камеру");
            }
        );
    } else {
        alert("UserMedia не поддерживается");
    }


    if(finalParams.inputId!=null) {
        finalParams.inputId.addEventListener('change', function(e){
            finalParams.video.style.display = "none";
            finalParams.canvas.style.display = "block";
            finalParams.btnTakePhoto.style.display="none";
            finalParams.btnReTakePhoto.style.display="inline-block";

            var urlFile = url.createObjectURL(e.target.files[0]);
            var img = new Image();
            img.onload = function() {
                context.drawImage(img,0,0,img.width,img.height);
                if(finalParams.fncTakePhoto != null) {
                    finalParams.fncTakePhoto();
                }
            }
            img.src = urlFile;

        }, false);
    }

    finalParams.btnTakePhoto.addEventListener("click",function(){
        draw(finalParams.video,context);
        finalParams.video.style.display = "none";
        finalParams.canvas.style.display = "block";
        finalParams.btnTakePhoto.style.display="none";
        finalParams.btnReTakePhoto.style.display="inline-block";
        if(finalParams.fncTakePhoto != null) {
            finalParams.fncTakePhoto();
        }
    });
    finalParams.btnReTakePhoto.addEventListener("click",function(){
        finalParams.canvas.style.display = "none";
        finalParams.video.style.display = "block";
        finalParams.btnTakePhoto.style.display="inline-block";
        finalParams.btnReTakePhoto.style.display="none";
        if(finalParams.fncReTakePhoto != null) {
            finalParams.fncReTakePhoto();
        }
    });




    function draw(video,context){
        context.drawImage(finalParams.video,0,0,finalParams.width,finalParams.height);
        context.setTransform(1, 0, 0, 1, 0, 0);
    }

}
