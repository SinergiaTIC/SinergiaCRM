var module = "TemplateSectionLine";

/**
 * Function to create an image and download it
 */
function createThumbnailNameImage() 
{
    // Create download button
    const btn = document.createElement('button');
    btn.textContent = '📷 ' + SUGAR.language.get(module, "LBL_THUMBNAIL_NAME_DOWNLOAD_BUTTON");
    btn.type = 'button';
    btn.className = 'btn btn-primary';
    btn.style.padding = '1rem';
    btn.style.margin = '1rem';
    
    btn.addEventListener('click', () => 
    {
        const thumbnailName = document.getElementById('thumbnail_name_c');
    
        if (!thumbnailName.value || thumbnailName.value.trim() === '') {
          alert(SUGAR.language.get(module, "LBL_THUMBNAIL_NAME_REQUIRED_TO_DOWNLOAD") + SUGAR.language.get(module, "LBL_THUMBNAIL_NAME"));
          return;
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
    
        html2canvas(tempDiv, {
            width: 225,
            height: 50,
            scale: 1
          }).then(canvas => {
            canvas.toBlob(blob => {
                const link = document.createElement('a');
                link.download = 'thumbnail_' + document.getElementById('thumbnail_name_c').value + '_' + Date.now();
                link.href = URL.createObjectURL(blob);
                link.click();
            });
        });
    });
  
    // Insert download button after the Thumbnail Name field
    const desc = document.getElementById('thumbnail_name_c');
    if (desc && desc.parentNode) {
      desc.parentNode.appendChild(btn);
    }
  }
  
  createThumbnailNameImage();