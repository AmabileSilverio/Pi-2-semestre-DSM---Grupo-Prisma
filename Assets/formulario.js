let currentStep = 1;
const totalSteps = 4;

function showStep(step) {
    // Esconde todas as etapas
    document.querySelectorAll('.form-step').forEach(formStep => {
        formStep.classList.remove('active');
    });

    // Mostra a etapa atual
    document.getElementById('step' + step).classList.add('active');

    // Atualiza a barra de progresso
    document.querySelectorAll('.step').forEach(stepEl => {
        stepEl.classList.remove('active', 'completed');
        if (parseInt(stepEl.dataset.step) < step) {
            stepEl.classList.add('completed');
        } else if (parseInt(stepEl.dataset.step) === step) {
            stepEl.classList.add('active');
        }
    });

    // Atualiza os botões
    const prevBtn = document.querySelector('.btn-prev');
    const nextBtn = document.querySelector('.btn-next');
    const submitBtn = document.querySelector('.submit-btn');

    prevBtn.style.display = step === 1 ? 'none' : 'block';
    nextBtn.style.display = step === totalSteps ? 'none' : 'block';
    submitBtn.style.display = step === totalSteps ? 'block' : 'none';
}

function nextStep() {
    if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

function showConfirmationModal(email) {
    const modal = document.getElementById('confirmationModal');
    const userEmail = document.getElementById('userEmail');
    userEmail.textContent = email;
    modal.style.display = 'flex';
}

function closeModal() {
    const modal = document.getElementById('confirmationModal');
    modal.style.display = 'none';
    window.location.href = '/Pi2/View/telaacompanhamento.php';
}

// Fecha modal se clicar fora dele
window.onclick = function (event) {
    const modal = document.getElementById('confirmationModal');
    if (event.target == modal) {
        closeModal();
    }
};

document.addEventListener('DOMContentLoaded', function () {
    showStep(1);

    const form = document.getElementById('inscricaoForm');
    const submitBtn = document.querySelector('.submit-btn');

    submitBtn.addEventListener('click', function (e) {
        e.preventDefault();
        enviarFormulario();
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        enviarFormulario();
    });

    function enviarFormulario() {
        const termsCheckbox = document.getElementById('terms');
        if (!termsCheckbox.checked) {
            alert('Por favor, leia e aceite os termos de uso para continuar.');
            return;
        }

        const formData = new FormData(form);

        // Adiciona os dias e horários marcados
        document.querySelectorAll('.dia-horario').forEach((dia) => {
            const checkbox = dia.querySelector('input[type="checkbox"]');
            if (checkbox.checked) {
                const diaNome = checkbox.value;

                const inicio = dia.querySelector('input[name^="inicio"]').value;
                const fim = dia.querySelector('input[name^="fim"]').value;

                formData.append(`horario_${diaNome}_inicio`, inicio);
                formData.append(`horario_${diaNome}_fim`, fim);
            }
        });

        // Envia via fetch
        fetch('/Pi2/Controller/formulario_hae.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === "ok") {
                showConfirmationModal(form.mail.value);
            } else {
                alert('Erro ao enviar: ' + data.mensagem);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao enviar o formulário. Por favor, tente novamente mais tarde.');
        });
    }
});

window.closeModal = closeModal;
