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
      fecharAnaliseRelatorioModal();
      abrirModalRelatorioAprovado();
      // Remova o location.reload() daqui, coloque no fecharModalRelatorioAprovado se quiser recarregar depois do OK
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

function abrirModalVerRelatorio(descricao, anexo, id) {
    document.getElementById('descricaoRelatorioView').textContent = descricao;
    const link = document.getElementById('anexoRelatorioView');
    if (anexo) {
        link.href = anexo;
        link.textContent = 'Baixar';
        link.style.display = '';
    } else {
        link.href = '#';
        link.textContent = 'Nenhum anexo enviado';
        link.style.display = '';
    }
    document.getElementById('relatorioIdView').value = id;
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
      fecharModalVerRelatorio();
      abrirModalRelatorioAprovado();
      // location.reload() já está no fecharModalCorrecaoSolicitada
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
      fecharModalVerRelatorio();
      abrirModalCorrecaoSolicitada();
      // location.reload() já está no fecharModalCorrecaoSolicitada
    } else {
      alert('Erro: ' + data.mensagem);
    }
  });
}

function formatarTipoHAE(tipo) {
    if (!tipo) return '';
    return tipo.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}

function abrirModalRelatorioAprovado() {
  document.getElementById('modalRelatorioAprovado').style.display = 'flex';
}
function fecharModalRelatorioAprovado() {
  document.getElementById('modalRelatorioAprovado').style.display = 'none';
  location.reload(); // recarrega a página ao fechar, se desejar
}

function abrirModalCorrecaoSolicitada() {
  document.getElementById('modalCorrecaoSolicitada').style.display = 'flex';
}
function fecharModalCorrecaoSolicitada() {
  document.getElementById('modalCorrecaoSolicitada').style.display = 'none';
  location.reload(); // recarrega a página ao fechar, se desejar
}
