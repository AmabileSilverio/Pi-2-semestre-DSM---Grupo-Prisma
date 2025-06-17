function abrirAnaliseRelatorioModal(descricao, anexoUrl, relatorioId) {
  document.getElementById('descricaoRelatorioProfessor').textContent = descricao;
  const link = document.getElementById('anexoRelatorioLink');
  if (anexoUrl) {
    link.href = anexoUrl;
    link.style.display = 'inline';
  } else {
    link.href = '#';
    link.style.display = 'none';
  }
  document.getElementById('relatorioIdAnalise').value = relatorioId;
  document.getElementById('parecerCoordenador').value = '';
  document.getElementById('analiseRelatorioModal').style.display = 'flex';
}

function fecharAnaliseRelatorioModal() {
  document.getElementById('analiseRelatorioModal').style.display = 'none';
}

function aprovarRelatorio() {
  const id = document.getElementById('relatorioIdAnalise').value;
  const parecer = document.getElementById('parecerCoordenador').value;
  if (!parecer.trim()) {
    alert('Digite o parecer do coordenador.');
    return;
  }
  fetch('/Pi2/Controller/aprovar_relatorio.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${encodeURIComponent(id)}&parecer=${encodeURIComponent(parecer)}`
  })
  .then(r => r.json())
  .then(data => {
    if (data.status === 'ok') {
      alert('Relatório aprovado!');
      fecharAnaliseRelatorioModal();
      location.reload();
    } else {
      alert('Erro: ' + data.mensagem);
    }
  });
}

function solicitarCorrecaoRelatorio() {
  const id = document.getElementById('relatorioIdAnalise').value;
  const parecer = document.getElementById('parecerCoordenador').value;
  if (!parecer.trim()) {
    alert('Digite o motivo da correção.');
    return;
  }
  fetch('/Pi2/Controller/solicitar_correcao_relatorio.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${encodeURIComponent(id)}&parecer=${encodeURIComponent(parecer)}`
  })
  .then(r => r.json())
  .then(data => {
    if (data.status === 'ok') {
      alert('Correção solicitada!');
      fecharAnaliseRelatorioModal();
      location.reload();
    } else {
      alert('Erro: ' + data.mensagem);
    }
  });
}

function abrirModalVerRelatorio(descricao, anexoUrl, relatorioId) {
  document.getElementById('descricaoRelatorioView').textContent = descricao;
  const link = document.getElementById('anexoRelatorioView');
  if (anexoUrl) {
    link.href = anexoUrl;
    link.style.display = 'inline';
  } else {
    link.href = '#';
    link.style.display = 'none';
  }
  document.getElementById('relatorioIdView').value = relatorioId;
  document.getElementById('parecerCoordenadorView').value = '';
  document.getElementById('verRelatorioModal').style.display = 'flex';
}

function fecharModalVerRelatorio() {
  document.getElementById('verRelatorioModal').style.display = 'none';
}

function aprovarRelatorioView() {
  const id = document.getElementById('relatorioIdView').value;
  const parecer = document.getElementById('parecerCoordenadorView').value;
  if (!parecer.trim()) {
    alert('Digite o parecer do coordenador.');
    return;
  }
  fetch('/Pi2/Controller/aprovar_relatorio.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${encodeURIComponent(id)}&parecer=${encodeURIComponent(parecer)}`
  })
  .then(r => r.json())
  .then(data => {
    if (data.status === 'ok') {
      alert('Relatório aprovado!');
      fecharModalVerRelatorio();
      location.reload();
    } else {
      alert('Erro: ' + data.mensagem);
    }
  });
}

function solicitarCorrecaoRelatorioView() {
  const id = document.getElementById('relatorioIdView').value;
  const parecer = document.getElementById('parecerCoordenadorView').value;
  if (!parecer.trim()) {
    alert('Digite o motivo da correção.');
    return;
  }
  fetch('/Pi2/Controller/solicitar_correcao_relatorio.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `id=${encodeURIComponent(id)}&parecer=${encodeURIComponent(parecer)}`
  })
  .then(r => r.json())
  .then(data => {
    if (data.status === 'ok') {
      alert('Correção solicitada!');
      fecharModalVerRelatorio();
      location.reload();
    } else {
      alert('Erro: ' + data.mensagem);
    }
  });
}