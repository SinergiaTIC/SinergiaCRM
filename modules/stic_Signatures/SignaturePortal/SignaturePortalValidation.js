// **************************************************************************
// OTP form handling
const otpForm = document.getElementById('otpForm');
if (otpForm) {


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

    // send OTP form

    otpForm.addEventListener('submit', (e) => {
        const otpCode = document.getElementById('otp-code');
        otpCode.value = Array.from(inputs).map(input => input.value).join('');
        
        // let newParam = 'otp-code';
        // let value = fullCode;
        // let url = new URL(window.location.href);
        // url.searchParams.set(newParam, value);
        // window.location.href = url.toString();


        // });


        // Agrega la lógica para el botón de reenviar OTP
        const resendButton = document.getElementById('resend-otp-btn');
        resendButton.addEventListener('click', () => {
            resendOtp();
        });


        /**
         *  Function to resend the OTP code to the user's email. 
         */
        function resendOtp() {


            const urlParams = new URLSearchParams(window.location.search);
            const url = 'index.php';
            const signerId = urlParams.get('signerId');

            const data = {
                entryPoint: "sticSign",
                signatureAction: "resendOtpCode",
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
                    throw new Error(SUGAR.language.get('stic_Signatures', 'LBL_PORTAL_NETWORK_ERROR'));
                }
                return response.json();
                // Parsea la respuesta como JSON
            }
            ).then(data => {
                alert(SUGAR.language.get('stic_Signatures', 'LBL_PORTAL_OTP_SENT'));
            }
            ).catch(error => {
                console.error('Error:', error);
                alert(SUGAR.language.get('stic_Signatures', 'LBL_PORTAL_ERROR_REQUEST_OTP_ALERT'));
            }
            );

        }

    });
}
// End OTP form handling
// **************************************************************************


