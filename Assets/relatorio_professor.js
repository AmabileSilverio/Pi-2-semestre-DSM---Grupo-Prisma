function abrirModalReenvio(idRelatorio) {
  document.getElementById('idRelatorioReenvio').value = idRelatorio;
  document.getElementById('descricaoReenvio').value = '';
  document.getElementById('anexoReenvio').value = '';
  document.getElementById('modalReenvio').style.display = 'flex';
}

function fecharModalReenvio() {
  document.getElementById('modalReenvio').style.display = 'none';
}

function enviarReenvioRelatorio() {
  const form = document.getElementById('formReenvio');
  const formData = new FormData(form);

  fetch('/Pi2/Controller/reenviar_relatorio.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === 'ok') {
      alert('Relatório enviado com sucesso!');
      fecharRelatorioModal();
      location.reload(); // Se quiser recarregar a página após o envio
    } else {
      alert('Erro ao enviar relatório: ' + (data && data.mensagem ? data.mensagem : ''));
    }
  })
  .catch(error => {
    alert('Erro ao reenviar relatório.');
    console.error(error);
  });
}
