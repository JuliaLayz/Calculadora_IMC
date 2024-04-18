<?php

require_once __DIR__ . '/src/Usuario.php';
require_once __DIR__ . '/src/CalculadoraImc.php';
require_once __DIR__ . '/src/SexoEnum.php';
require_once __DIR__ . '/src/ClassificacaoImcEnum.php';


$usuario = new Usuario( nome: $_POST['nome'], 
                        peso: $_POST['peso'], 
                        altura: $_POST['altura'],
                        sexo: SexoEnum::from($_POST['sexo']),
                        dataNascimento: new DateTimeImmutable($POST['data_nascimento']));

    if ($peso === null || $altura === null ) {
         throw new DadosInsuficientesException("Dados insuficientes para realizar o cálculo IMC");
 }
                
    if ($peso <= 0 || $altura <= 0){
         throw new DadosInsuficientesException("Peso e altura devem ser maiores que zero para calcular IMC");
}


$calculadora = new CalculadoraImc($usuario);
$resultado = $calculadora->classificarIdade($usuario->getIdadeAtual(), $usuario->getSexo()->value);

// 1) ler o template de resposta
$template = file_get_contents(__DIR__ . '/src/templates/resultado.html');

// 2) trocar cada valor estatico pelo valor do script
$template = str_replace(
    [
        '{{USUARIO}}',
        '{{PESO}}',
        '{{ALTURA}}',
        '{{IDADE}}',
        '{{SEXO}}',
        '{{ICM}}',
        '{{CLASSIFICACAO}}'
    ],
    [
        $usuario->getNome(),
        $usuario->getPeso(),
        $usuario->getAltura(),
        $usuario->getIdadeAtual(),
        $usuario->getSexo()->value,
        $calculadora->calcular(),
        $resultado
    ],
    $template);


echo $template;