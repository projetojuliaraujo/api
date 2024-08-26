<?php
    header('Content-Type:application/json');
    include 'conexao.php';

    $metodo = $_SERVER['REQUEST_METHOD'];
    $url = $_SERVER['REQUEST_URI'];
    $path = parse_url($url, PHP_URL_PATH);
    $path = trim($path,'/');
    $pathparts = explode('/',$path);

    $primeira = isset($pathparts[0]) ? $pathparts[0] : ''; 
    $segunda = isset($pathparts[1]) ? $pathparts[1] : '';
    $terceira = isset($pathparts[2]) ? $pathparts[2] : '';
    $quarta = isset($pathparts[3]) ? $pathparts[3] : '';

    $response = [
        'metodo' => $metodo,
        'primeiraparte' => $primeira,
        'segundaparte' => $segunda,
        'terceiraparte' => $terceira,
        'quartaparte' => $quarta
    ];


    switch($metodo){
        case 'GET':
            //LÓGICA PARA GET
            if($terceiraparte == 'alunos' && $quartaparte ==''){
                lista_alunos();
            }
            elseif($terceiraparte == 'alunos' && $quartaparte !=''){
                lista_um_aluno($quartaparte);
            }
            elseif($terceiraparte == 'cursos' && $quartaparte == ''){
                lista_cursos();
            }
            elseif($terceiraparte == 'cursos' && $quartaparte !=''){
                lista_um_curso($quartaparte);
            }
            break;
        case 'POST':
            //LÓGICA PARA POST
            if ($terceiraparte == 'alunos'){
                insere_aluno();
            }
            elseif ($terceiraparte == 'cursos'){
                insere_curso();
            }
            break;
        case 'PUT':
            //LÓGICA PARA PUT
            if ($terceiraparte == 'alunos'){
                atualiza_alunos();
            }
            elseif ($terceiraparte == 'cursos') {
                atualiza_curso();
            }
            break;
        case 'DELETE':
            //LÓGICA PARA DELETE
            if ($terceiraparte == 'alunos'){
                remove_aluno();
            }
            elseif ($terceiraparte == 'cursos') {
                remove_curso();
            }
            break;
        default:
            echo json_encode(
                [
                    'mensagem' => 'Método não permitido!'
                ]
            );
            break;
    }



    function lista_alunos(){
        global $conexao;
        $resultado = $conexao->query("SELECT * FROM alunos");
        $alunos = $resultado->fetch_all(MYSQLI_ASSOC);
        echo json_encode(
            [
                'mensagem' => 'LISTA TODOS OS ALUNOS!',
                'dados' => $alunos
            ]
        );
    }

    function lista_um_aluno($quarta){
        global $conexao;
        $stmt = $conexao->prepare("SELECT * FROM alunos WHERE id = ?");
        $stmt->bind_param('i',$quarta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $aluno = $resultado->fetch_assoc();

        if($aluno == ''){
            echo json_encode(
                [
                    'mensagem' => 'NÃO FOI ENCONTRADO O ALUNO ACIMA!'
                ]
            );
        }else{
            echo json_encode(
                [
                    'mensagem' => 'LISTA DE UM ALUNO!',
                    'dados_aluno' => $aluno
                ]
            );
        }

        
    }

    function lista_cursos(){
        global $conexao;
        $resultado = $conexao->query("SELECT * FROM cursos");
        $cursos = $resultado->fetch_all(MYSQLI_ASSOC);
        echo json_encode(
            [
                'mensagem' => 'LISTA TODOS OS CURSOS!',
                'dados' => $cursos
            ]
        );
    }

    function lista_um_curso($quartaparte){
        global $conexao;
        $stmt = $conexao->prepare("SELECT * FROM cursos WHERE id_curso = ?");
        $stmt->bind_param('i',$quarta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $curso = $resultado->fetch_assoc();

        echo json_encode([
            'mensagem' => 'LISTA DE UM CURSO',
            'dados_aluno' => $curso
        ]);
    }


    function insere_curso(){
        global $conexao;
        //OPCAO 1 COM JSON
        // $input = json_decode(file_get_contents('php://input'), true);
        // $nome_curso = $input['nome_curso'];

        //OPCAO 2 COM PARAMETROS
        $nome_curso = $_GET['nome_curso'];

        $sql = "INSERT INTO cursos (nome_curso) VALUES ('$nome_curso')";

        if($conexao->query($sql) == TRUE){
            echo json_encode([
                'mensagem' => 'CURSO CADASTRADO COM SUCESO'
            ]);
        }
        else {
            echo json_encode([
                'mensagem' => 'ERRO NO CADASTRO DO CURSO'
            ]);
        }
    }










        if($curso == ''){
            echo json_encode(
                [
                    'mensagem' => 'NÃO FOI ENCONTRADO O CURSO ACIMA!'
                ]
            );
        }else{
            echo json_encode(
                [
                    'mensagem' => 'LISTA DE UM CURSO!',
                    'dados_curso' => $curso
                ]
            );
        }

        
    }


?>