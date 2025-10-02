<div id="pge-consult-component">
    <form id="pge-consult-form">
        <label for="documento">Documento (CPF/CNPJ)</label>
        <input id="documento" name="documento" type="text" required placeholder="000.000.000-00" />
        <button id="pge-consult-submit" type="submit">Consultar</button>
    </form>

    <div id="pge-consult-ui" style="margin-top:12px;">
        <div id="pge-consult-spinner" style="display:none; margin-bottom:8px;">⏳ Consultando...</div>
        <div id="pge-consult-result" style="white-space:pre-wrap; border-radius:4px; padding:8px;"></div>
    </div>

    <script>
        (function(){
            const form = document.getElementById('pge-consult-form');
            const result = document.getElementById('pge-consult-result');
            const btn = document.getElementById('pge-consult-submit');

            function getCsrfToken(){
                const m = document.querySelector('meta[name="csrf-token"]');
                return m ? m.getAttribute('content') : '';
            }

            // Máscara simples para CPF/CNPJ (apenas formatação visual)
            const docInput = document.getElementById('documento');
            docInput.addEventListener('input', function(e){
                let v = e.target.value.replace(/\D/g, '');
                if (v.length <= 11) {
                    v = v.replace(/(\d{3})(\d)/, '$1.$2');
                    v = v.replace(/(\d{3})\.(\d{3})(\d)/, '$1.$2.$3');
                    v = v.replace(/(\d{3})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3-$4');
                } else {
                    // simples formatação de CNPJ
                    v = v.replace(/(\d{2})(\d)/, '$1.$2');
                    v = v.replace(/(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    v = v.replace(/(\d{2})\.(\d{3})\.(\d{3})(\d)/, '$1.$2.$3/$4');
                    v = v.replace(/(\d{2})\.(\d{3})\.(\d{3})\/(\d{4})(\d)/, '$1.$2.$3/$4-$5');
                }
                e.target.value = v;
            });

            function onlyDigits(str){
                return str.replace(/\D/g,'');
            }

            function isValidCPF(cpf){
                cpf = onlyDigits(cpf);
                if (cpf.length !== 11) return false;
                // rejeita sequências repetidas
                if (/^(\d)\1{10}$/.test(cpf)) return false;

                const digits = cpf.split('').map(d => parseInt(d,10));

                const calc = (slice) => {
                    let sum = 0;
                    for (let i=0; i<slice; i++){
                        sum += digits[i] * (slice+1 - i);
                    }
                    const res = (sum * 10) % 11;
                    return res === 10 ? 0 : res;
                };

                const v1 = calc(9);
                const v2 = ( (digits.slice(0,10).reduce((s,d,i)=> s + d*(11-i),0) * 10) % 11 ) === 10 ? 0 : ((digits.slice(0,10).reduce((s,d,i)=> s + d*(11-i),0) * 10) % 11);

                return digits[9] === v1 && digits[10] === v2;
            }

            function showError(msg){
                result.style.background = '#ffefef';
                result.style.border = '1px solid #ffb5b5';
                result.textContent = msg;
            }

            function showSuccess(data){
                result.style.background = '#f6ffef';
                result.style.border = '1px solid #c6f0b3';
                result.textContent = data;
            }

            form.addEventListener('submit', async function(e){
                e.preventDefault();
                result.textContent = '';
                result.style.border = 'none';
                btn.disabled = true;
                const documentoRaw = document.getElementById('documento').value.trim();
                const documento = onlyDigits(documentoRaw);

                // validação cliente: CPF (se 11 dígitos)
                if (documento.length === 11 && !isValidCPF(documento)){
                    showError('CPF inválido. Verifique os dígitos e tente novamente.');
                    btn.disabled = false;
                    return;
                }

                // mostra spinner
                const spinner = document.getElementById('pge-consult-spinner');
                spinner.style.display = 'block';

                try {
                    const resp = await fetch('/pge/consult', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        credentials: 'include',
                        body: JSON.stringify({ documento })
                    });

                    const json = await resp.json();
                    if (!resp.ok) {
                        showError(json.error || JSON.stringify(json));
                    } else {
                        showSuccess(JSON.stringify(json.data || json, null, 2));
                    }
                } catch (err) {
                    showError('Erro de rede: ' + err.message);
                } finally {
                    spinner.style.display = 'none';
                    btn.disabled = false;
                }
            });
        })();
    </script>
</div>
