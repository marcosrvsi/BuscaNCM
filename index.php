<?php
// if (!isset($_SESSION['cnpj'])) exit;
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title>Consulta de NCM com Reforma Tributária</title>
        <meta name="description" content="Consulte o NCM com as regras da reforma tributária, incluindo IBS e CBS. Tabela atualizada e explicação simples.">
        <link rel="canonical" href="https://mvsistema.com/BuscaNCM/">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <style>
            body {
                background-color: #f5f6f8;
            }

            th, td {
                font-size: 14px;
                vertical-align: top;
                word-wrap: break-word;
            }

            th {
                text-align: center;
                white-space: nowrap;
            }

            .table td {
                white-space: normal;
            }

            .badge {
                font-size: 12px;
            }

            .aviso-fiscal {
                font-size: 14px;
            }
        </style>
    </head>

    <body class="container py-4">

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h1 class="mb-0">
                    <i class="bi bi-search"></i> Consulta de NCM com Reforma Tributária
                </h1>
            </div>

            <div class="card-body">
                <!-- TEXTO DESCRITIVO PARA SEO -->
                <div class="mb-4">
                    <h2 class="h5 text-muted">
                        Consulte o NCM com as regras do IBS e da CBS na Reforma Tributária
                    </h2>
                    <p>
                        Consulte o NCM e visualize as regras de tributação conforme a
                        reforma tributária brasileira, incluindo IBS, CBS, CST e
                        classificações tributárias oficiais (cClassTrib).
                    </p>
                </div>
                <!-- CONSULTA -->
                <div class="row g-3 align-items-end mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Código NCM</label>
                        <input id="ncm" class="form-control" placeholder="Ex: 01012100">
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" onclick="consultar()">
                            <i class="bi bi-search"></i> Consultar
                        </button>
                    </div>
                </div>

                <!-- AVISO INSTITUCIONAL / FISCAL1 -->
                <div class="alert alert-light border aviso-fiscal mb-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                        <div class="text-muted fst-italic">
                            <strong>Observação:</strong> As informações apresentadas nesta consulta possuem caráter
                            <strong>meramente informativo</strong>, sendo obtidas a partir dos dados disponibilizados no
                            portal oficial da Secretaria da Fazenda, por meio do endereço eletrônico: 
                            <a href="https://dfe-portal.svrs.rs.gov.br/CFF/ClassificacaoTributaria" target="_blank">
                                dfe-portal.svrs.rs.gov.br
                            </a>.
                            <br>
                            Tais informações <strong>não substituem a análise técnica</strong> e
                            <strong>não devem ser aplicadas</strong> sem a prévia consulta a um
                            <strong>profissional habilitado e responsável pela área fiscal da empresa</strong>.
                            <br>

                        </div>
                    </div>
                </div>
                <!-- AVISO INSTITUCIONAL / FISCAL2 -->
                <div class="alert alert-light border aviso-fiscal mb-3">
                    <div class="d-flex align-items-start">
                        <i class="bi bi-info-circle-fill text-primary me-2 mt-1"></i>
                        <div class="text-muted fst-italic">
                            Caso nenhuma das classificações tributárias (<strong>cClassTrib</strong>) apresentadas
                            se enquadre ao Produtos que o NCM esta vinculado, deverá ser adotada, em caráter subsidiário,
                            a classificação: 
                            <br>
                            <strong>CST = 000</strong>, <strong>cClassTrib = 000001</strong>
                            <br>
                            Conforme a regra geral da
                            <a href="https://www.planalto.gov.br/ccivil_03/leis/lcp/lcp214.htm#art4"
                               target="_blank" rel="noopener noreferrer">
                                <strong>Lei Complementar nº 214/2025</strong>
                            </a>.


                            <p class="mb-2">
                                Nos termos da legislação vigente, o <strong>IBS</strong> e a <strong>CBS</strong>
                                incidem sobre operações onerosas, presumindo-se, como regra geral,
                                que <strong>todo item é tributado</strong>, salvo exceção legal expressa.
                            </p>
                            <br>

                        </div>
                    </div>
                </div>
                
                <div class="alert alert-light border aviso-fiscal mb-3">
                    <!-- RESULTADO -->
                    <div id="resultado"></div>
                </div>
                
            </div>

            <script>
                function consultar() {
                    let codigo = document.getElementById('ncm').value;
                    const resultado = document.getElementById('resultado');
                    if (!codigo) {
                        resultado.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Informe o NCM
                        </div>`;
                        return;
                    }
                    // Remove tudo que não for número
                    codigo = codigo.replace(/\D/g, '');
                    // Valida tamanho do NCM
                    if (codigo.length != 8) {
                        resultado.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i>
                    NCM com tamanho incorreto.<br>
                    Informe um NCM com <strong>8 dígitos numéricos</strong>.
                </div>`;
                        return;
                    }
                    resultado.innerHTML = `
                    <div class="text-center my-4">
                        <div class="spinner-border text-primary"></div>
                        <div class="mt-2">Consultando...</div>
                    </div>`;
                    fetch('proxy_ncm.php?ncm=' + encodeURIComponent(codigo))
                            .then(res => res.json())
                            .then(json => {

                                if (json.status !== "200" || !json.results || json.results.length === 0) {
                                    resultado.innerHTML = `
                                <div class="alert alert-danger">
                                    <strong>Nenhum resultado encontrado.</strong><br>
                                    Caso o NCM seja válido, utilize:
                                    <ul class="mb-0">
                                        <li>CST: <strong>0000</strong></li>
                                        <li>Classificação (cClassTrib): <strong>000001</strong></li>
                                    </ul>
                                </div>`;
                                    return;
                                }

                                let html = `
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>NCM</th>
                                            <th>Descrição</th>
                                            <th>CST</th>
                                            <th>cClassTrib</th>
                                            <th>Tipo</th>
                                            <th>Descrição cClassTrib</th>
                                            <th>% IBS</th>
                                            <th>% CBS</th>
                                            <th>Link Anexo</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;
                                json.results.forEach(item => {
                                    html += `
                                <tr>
                                    <td class="text-center"><strong>${item.codigo}</strong></td>
                                    <td>${item.descricao}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">${item.cst}</span>
                                    </td>
                                    <td class="text-center"><strong>${item.cclasstrib}</strong></td>
                                    <td class="text-center">${item.tipocclasstrib}</td>
                                    <td>${item.descricao_cclasstrib}</td>
                                    <td class="text-center">${item.predibs ?? '0'}%</td>
                                    <td class="text-center">${item.predcbs ?? '0'}%</td>
                                    <td class="text-center">
                                        ${item.link
                                    ? `<a href="${item.link}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                               </a>`
                                    : '-'}
                                    </td>
                                </tr>`;
                                });
                                html += `
                                    </tbody>
                                </table>
                            </div>`;
                                resultado.innerHTML = html;
                            })
                            .catch(() => {
                                resultado.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bi bi-x-circle"></i> Erro ao consultar a API
                            </div>`;
                            });
                }
            </script>

    </body>
</html>
