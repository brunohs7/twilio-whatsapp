<?php

// Função para decodificar o texto de QUOTED-PRINTABLE
function decodeQuotedPrintable($input) {
    return quoted_printable_decode($input);
}

// Função para converter VCF para CSV
function vcfToCsv($vcfFile, $csvFile) {
    // Verifica se o arquivo VCF existe
    if (!file_exists($vcfFile)) {
        echo "Arquivo VCF não encontrado!";
        return;
    }

    // Lê o conteúdo do arquivo VCF
    $vcfData = file_get_contents($vcfFile);
    
    // Divide o arquivo VCF por cada cartão de contato (vcards)
    $vcards = explode("END:VCARD", $vcfData);
    
    // Abre o arquivo CSV para escrita
    $csvHandle = fopen($csvFile, 'w');
    
    // Escreve o cabeçalho no CSV
    fputcsv($csvHandle, ['Nome', 'Telefone']);
    
    // Loop por cada vCard
    foreach ($vcards as $vcard) {
        // Ignora a primeira parte que não contém dados válidos
        if (empty($vcard) || strpos($vcard, 'BEGIN:VCARD') === false) {
            continue;
        }

        // Extrai o nome do campo FN (nome completo)
        preg_match('/FN:([^\\n]+)/', $vcard, $nameMatches);
        $name = isset($nameMatches[1]) ? decodeQuotedPrintable(trim($nameMatches[1])) : '';

        // Extrai o telefone do campo TEL
        preg_match('/TEL;[^:]+:(.*)/', $vcard, $phoneMatches);
        $phone = isset($phoneMatches[1]) ? trim($phoneMatches[1]) : '';

        // Escreve os dados no arquivo CSV
        fputcsv($csvHandle, [$name, $phone]);
    }

    // Fecha o arquivo CSV
    fclose($csvHandle);

    echo "Conversão concluída! O arquivo CSV foi salvo em $csvFile.";
}

// Chama a função para converter o arquivo VCF para CSV
vcfToCsv('contatos.vcf', 'contatos.csv');

?>
