var module = "TemplateSectionLine";

/* VIEWS CUSTOM CODE */
switch (viewType()) {
  case "edit":
  case "quickcreate":
  case "popup":
    createThumbnailImageButton();
    break;

  case "detail":
    break;

  case "list":
    break;

  default:
    break;
}

/**
 * Function that displays the button to automatically generate and select the thumbnail image with html2canvas library
 */
function createThumbnailImageButton() {
  // Create load image button
  const btn = document.createElement('button');
  btn.textContent = '📷 ' + SUGAR.language.get(module, "LBL_THUMBNAIL_IMAGE_CREATE_IMAGE_BUTTON");
  btn.type = 'button';
  btn.className = 'btn btn-primary';
  btn.style.padding = '1rem';
  btn.style.marginTop = '1rem';

  // Insert load image button below the Thumbnail Image field
  const thumbnailImage = document.getElementById('thumbnail_image_c');
  if (thumbnailImage && thumbnailImage.parentNode) {
    thumbnailImage.parentNode.appendChild(btn);
  }

  btn.addEventListener('click', () => 
  {
    // Show an alert if the name for the thumbnail does not exist
    const thumbnailName = document.getElementById('thumbnail_name_c');
    if (!thumbnailName.value || thumbnailName.value.trim() === '') {
      alert(SUGAR.language.get(module, "LBL_THUMBNAIL_NAME_REQUIRED_TO_CREATE_IMAGE") + SUGAR.language.get(module, "LBL_THUMBNAIL_NAME"));
      return;
    }

    // If there is already a file in the Thumbnail Image field, it is deleted
    const thumbnailImageFile = document.getElementById('thumbnail_image_c_file');
    const removeSpanElement = document.getElementById('thumbnail_image_c_old');
    if (thumbnailImageFile.value.trim() != '' || removeSpanElement.style.display != 'none') {
      if (confirm(SUGAR.language.get(module, "LBL_THUMBNAIL_IMAGE_CREATE_IMAGE_CONFIRM"))) {
        thumbnailImageFile.value = '';
        const removeButton = document.getElementById('remove_button');
        if (removeButton) {
          removeButton.onclick();
        }
      } else {
        return;
      }
    }

    // Create a temporary container with the content to scale to the thumbnail size
    const tempDiv = document.createElement('div');
    tempDiv.textContent = thumbnailName.value;
    tempDiv.style.width = '225px';
    tempDiv.style.height = '50px';
    tempDiv.style.fontSize = '20px';
    tempDiv.style.padding = '4px';
    tempDiv.style.display = 'flex';
    tempDiv.style.alignItems = 'center';
    tempDiv.style.justifyContent = 'center';
    tempDiv.style.textAlign = 'center';
    tempDiv.style.border = '2px solid #ccc';
    tempDiv.style.background = 'white';
    tempDiv.hidden = true; 
    document.body.appendChild(tempDiv);

    // Create the image with width: 225px and height: 50px
    html2canvas(tempDiv, {
        width: 225,
        height: 50,
        scale: 1
      }).then(canvas => {
        canvas.toBlob(blob => 
          {
            // Create file with the image blob
            const imageFile = new File([blob], 'thumbnail_' + document.getElementById('thumbnail_name_c').value + '_' + Date.now() + '.png', { type: 'image/png' });

            // Load the imagefile in the input of type file (thumbnail_image_c_file) related to the input of type image (thumbnail_image_c)
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(imageFile);
            const imageFileInput = document.getElementById('thumbnail_image_c_file');
            imageFileInput.files = dataTransfer.files;
        });
    });
  });
}