// Select the drop zone element
const dzs = document.querySelectorAll('.dropzone');

// check for any dropzones
if ( dzs.length > 0 ) {

    // look for all uploaded documents' delete buttons and add click event to delete the file
    let dz_i;
    const del_btns = document.querySelectorAll('.btn_delete');

    for ( dz_i = 0; dz_i < del_btns.length; dz_i++ ) {
        del_btns[dz_i].addEventListener("click", deleteFile, false);
    }

    // look for all dropzones and add all drag events
    for ( dz_i = 0; dz_i < dzs.length; dz_i++ ) {
        // Prevent default drag behaviors
        ["dragenter", "dragover", "dragleave", "drop"].forEach(eventName => {
            dzs[dz_i].addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when dragging over it
        ["dragenter", "dragover"].forEach(eventName => {
            dzs[dz_i].addEventListener(eventName, highlight, false);
        });

        // Un-highlight drop zone when dragging leaves it
        ["dragleave", "drop"].forEach(eventName => {
            dzs[dz_i].addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dzs[dz_i].addEventListener("drop", handleDrop, false);
    }

    // map manual upload buttons for auto upload
//    const manual_upload_btn = document.getElementById('manual_upload_btn_image');
    const manual_upload_btn = document.querySelectorAll('.upload-btn');
    const manual_input = document.querySelectorAll('.upload-input');

    if ( manual_upload_btn.length > 0 ) {
        for ( let mu_i = 0; mu_i < manual_upload_btn.length; mu_i++ ) {
            manual_upload_btn[mu_i].addEventListener('click', () => {
                manual_upload_btn[mu_i].nextElementSibling.click();
            });

            manual_input[mu_i].addEventListener("change", () => {
                document.getElementById('main_overlay_bg').classList.toggle('show');
                document.getElementById('main_overlay_content').classList.toggle('show');
                manual_input[mu_i].parentElement.submit();
            });
        }
    }
}


function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function highlight() {
    this.classList.add("highlight");
}

function unhighlight() {
    this.classList.remove("highlight");
}

function handleDrop(e) {
    var files = e.dataTransfer.files;
    uploadFiles(files, this.id);
}

function error_hide() {
    const img_err = document.getElementById('img_error_div');
    const oneline_err = document.getElementById('oneline_error_div');
    const doc_err = document.getElementById('doc_error_div');

    if ( !img_err.classList.contains('d-none') )  { img_err.classList.add('d-none'); }
    if ( !oneline_err.classList.contains('d-none') )  { oneline_err.classList.add('d-none'); }
    if ( !doc_err.classList.contains('d-none') )  { doc_err.classList.add('d-none'); }

    return true;
}

function uploadFiles(files, id) {
    // use id to identify the proper section
    // split via _ and pop the end
    const sect = id.split('_').pop();

    error_hide();

    const img_err = document.getElementById('img_error_div');
    const oneline_err = document.getElementById('oneline_error_div');
    const doc_err = document.getElementById('doc_error_div');

    for ( let i = 0; i < files.length; i++ ) {
        // get the file extension
        const file_ext = files[i].name.substring(files[i].name.lastIndexOf('.') + 1, files[i].name.length) || files[i].name;

        // check file size
        const f_size = files[i].size / 1024;

        if (f_size > 10240) {
            alert('The file "' + files[i].name + '" is larger than 10MB. Please resize and try again.');
        } else if (sect == 'image' && file_ext != 'jpg' && file_ext != 'jpeg' && file_ext != 'png' && file_ext != 'webp' && file_ext != 'gif') {
            img_err.classList.remove('d-none');
        } else if (sect == 'oneline' && file_ext != 'pdf' && file_ext != 'docx') {
            oneline_err.classList.remove('d-none');
        } else if (sect == 'doc' && file_ext != 'pdf' && file_ext != 'docx' && file_ext !='doc' && file_ext != 'xls' && file_ext != 'xlsx' && file_ext != 'txt' && file_ext != 'csv' && file_ext != 'ppt' && file_ext != 'pptx') {
            doc_err.classList.remove('d-none');
        } else {
            // The timeout allows each item (if multiple) to fully process before starting the next.
            // This avoids the potential to end up with duplicate ids in the database
            setTimeout(() => {
                const formData = new FormData();
                const csrf = document.querySelector('[name="_token"]');

                formData.append("file", files[i]);
                formData.append("_token", csrf.value);
                formData.append('type', sect);

                const xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax", true);
                xhr.onload = function () {
                    if (xhr.readyState == 4 && xhr.status === 200) {
                        let new_link, new_icon;

                        // responseText returns two comma-delimited items : id, saved image name/link
                        const response = xhr.responseText.split(',');

                        // Identify the proper section to display uploaded images
                        const img_display = document.getElementById('img_display_' + sect);

                        // create the delete button id for each image
                        const new_del = 'del_' + response[0];

                        // Create the new image container, delete button a tag, and the image icon tag
                        const new_div = document.createElement('div');
                        const new_a = document.createElement('a');
                        const new_img = document.createElement('img');

                        // Create the link to the image for the above a tag
                        new_link = '/storage/' + response[1];

                        // Get the icon link
                        new_icon = '/file_icons/' + file_ext + '.svg';

                        new_img.classList.add('thumbnail');

                        // Build the div container, image, and link
                        new_div.classList.add('file_upload');
                        new_div.setAttribute('id', 'file_' + response[0]);
                        new_img.src = new_icon;

                        new_a.href = new_link;
                        new_a.append(new_img);
                        new_a.append(files[i].name);
                        new_div.append(new_a);
                        new_div.innerHTML += '<button id ="' + new_del + '" class="btn_delete">&#x2715;</button>';
                        img_display.append(new_div);

                        // check for amount of uploads for section and hide "no files found" text if at least one
                        if (img_display.children.length > 1) {
                            img_display.querySelector('.img-count').classList.add('d-none');
                        }
                        // set the event listener for the new delete button
                        document.getElementById(new_del).addEventListener('click', deleteFile, false);
                    } else {
                        console.log(xhr.responseText);
                        alert("Error uploading files.");
                    }
                }
                xhr.send(formData);
            }, 500);
        }
    }
}
function deleteFile() {
    error_hide();

    // Get the parent node of the button
    const parent_div = this.parentNode;
    const section = parent_div.parentNode.id.split('_').pop();
    const img_display = document.getElementById('img_display_' + section);
    const csrf = document.querySelector('[name="_token"]');

    // split the id of the parent to get the INT for mysql deletion
    const split_id = parent_div.id.split('_');

    const formData = new FormData();
    formData.append('file_id', + split_id[1]);
    formData.append("_token", csrf.value);

    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'ajax-delete', true);

    xhr.onreadystatechange = function() {
        if(xhr.readyState == 4 && xhr.status == 200) {
            parent_div.style.opacity = 0;
            setTimeout( () => {
                parent_div.remove();
            }, 200);
        }
    }
    xhr.send(formData);

    setTimeout( () => {
        console.log(img_display);
        // Check if there are any files to display, if now show the "no images" text
        const files_left = img_display.querySelectorAll('.file_upload');
        console.log(files_left.length);

        if ( files_left.length == 0 ) {
            img_display.querySelector('.img-count').classList.remove('d-none');
        }
    }, 600);

}

