const inputs = document.querySelectorAll('.otp-input');
inputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

const form = document.getElementById('otpForm');
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const fullCode = Array.from(inputs).map(input => input.value).join('');

    let newParam = 'otp-code';
    let value = fullCode;
    let url = new URL(window.location.href);
    url.searchParams.set(newParam, value);
    window.location.href = url.toString();


});

// Agrega la lógica para el botón de reenviar OTP
const resendButton = document.getElementById('resend-otp-btn');
resendButton.addEventListener('click', () => {
    resendOtp();
});


/**
 *  Function to resend the OTP code to the user's email. 
 */
function resendOtp() {
    console.log("Solicitando un nuevo código OTP...");


    const urlParams = new URLSearchParams(window.location.search);
    const url = 'index.php';
    const signerId = urlParams.get('signerId');

    const data = {
        module: "stic_Signatures",
        action: "resendOtpCode",
        signerId: signerId,
    };

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams(data),
    }).then(response => {

        // Manage the response
        if (!response.ok) {
            throw new Error('Error de red o del servidor');
        }
        return response.json();
        // Parsea la respuesta como JSON
    }
    ).then(data => {
        console.log('Nuevo OTP solicitado:', data);
        alert('Se ha enviado un nuevo código OTP. Por favor, revise su bandeja de entrada.');
    }
    ).catch(error => {
        console.error('Error al solicitar OTP:', error);
        alert('Ha ocurrido un error al solicitar el código OTP.');
    }
    );

}


// function sendOtpCodeForCheck() {
//     const urlParams = new URLSearchParams(window.location.search);
//     const url = 'index.php';
//     const signerId = urlParams.get('signerId');
//     const otpCode = Array.from(inputs).map(input => input.value).join('');
//     console.log(otpCode)
//     // check if otpCode  is 6 digits
//     if (otpCode.length !== 6 || isNaN(otpCode)) {
//         alert('Por favor, introduce un código de verificación válido de 6 dígitos.');
//         return;
//     }

//     const data = {
//         module: "stic_Signatures",
//         action: "verifyOtpCode",
//         signerId: signerId,
//         otpCode: otpCode
//     };

//     fetch(url, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded',
//         },
//         body: new URLSearchParams(data),
//     }).then(response => {

//         // Manage the response
//         if (!response.ok) {
//             throw new Error('Error de red o del servidor');
//         }
//         return response.json();
//         // Parsea la respuesta como JSON
//     }
//     ).then(data => {
//         console.log('Respuesta de verificación OTP:', data);
//         if (data == true) {
//             console.log('Código OTP verificado correctamente. Procediendo a la firma del documento.');
//             // Redirigir o proceder con la firma
//         } else {
//             alert('Código OTP incorrecto. Por favor, inténtelo de nuevo.');
//         }
//     }
//     ).catch(error => {
//         console.error('Error al verificar OTP:', error);
//         alert('Ha ocurrido un error al verificar el código OTP.');
//     }
//     );

// }