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
    console.log("Código OTP completo:", fullCode);
    // Aquí puedes enviar el código completo al servidor
});

// Agrega la lógica para el botón de reenviar OTP
const resendButton = document.getElementById('resend-otp-btn');
resendButton.addEventListener('click', () => {
    console.log("Solicitando un nuevo código OTP...");


    const urlParams = new URLSearchParams(window.location.search);
    const url = 'index.php';
    const signerId = urlParams.get('signerId');
    // Asegúrate de que esta variable tenga el valor correcto en tu contexto

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

        console.log('Respuesta del servidor recibida:', response);
        // Manejar la respuesta del servidor
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




});