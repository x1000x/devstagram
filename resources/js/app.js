import Dropzone from "dropzone";

Dropzone.autoDiscover = false;

const dropzone = new Dropzone('#dropzone',{
    dictDefaultMessage: "Sube aqui tu imagen",
    acceptedFiles: ".png, .jpeg, .gif, .jpg",
    addRemoveLinks: true,
    dictRemoveFile: "Borrar archivo",
    maxfiles: 1,
    uploadMultiple: false,

    init: function(){
        if (document.querySelector('[name="imagen"]').value.trim()){
            const imagenPublicada= {};
             imagenPublicada.siza =1234;
             imagenPublicada.name= document.querySelector('[name="imagen"]').value;

             this.options.addedfile.call(this, imagenPublicada);
             this.options.thumbnail.call(
                this,
                imagenPublicada,
                `/uploads/${imagenPublicada.name}`
             );

             imagenPublicada.previewElement.classList.add(
                "dz-success",
                "dz-complete",
             );

        }
    },
    
});

dropzone.on('success', function(file, response){
document.querySelector('[name="imagen"]').value =response.imagen;

})

dropzone.on("removedfile", function(){
    document.querySelector('[name= "imagen"]').value="";
});