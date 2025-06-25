document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editalForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('/Pi2/Controller/salvar_edital.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'ok') {
                abrirModalSucessoEdital();
            } else {
                alert('Erro ao cadastrar edital: ' + (data.mensagem || ''));
            }
        })
        .catch(error => {
            alert('Erro ao cadastrar edital.');
            console.error(error);
        });
    });
});

function abrirModalSucessoEdital() {
    document.getElementById('modalSucessoEdital').style.display = 'flex';
}
function fecharModalSucessoEdital() {
    document.getElementById('modalSucessoEdital').style.display = 'none';
    window.location.href = 'listaeditalcoordenador.php';
}

let editalParaExcluir = null;

function abrirModalExcluirEdital(id) {
  editalParaExcluir = id;
  document.getElementById('modalExcluirEdital').style.display = 'flex';
}

function fecharModalExcluirEdital() {
  editalParaExcluir = null;
  document.getElementById('modalExcluirEdital').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function() {
  const btn = document.getElementById('btnConfirmarExclusao');
  if (btn) {
    btn.onclick = function() {
      if (editalParaExcluir) {
        window.location.href = '../Controller/excluir_edital.php?id=' + editalParaExcluir;
      }
    }
  }
});
