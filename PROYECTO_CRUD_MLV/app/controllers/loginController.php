<?php

	namespace app\controllers;
	use app\models\mainModel;

	class loginController extends mainModel{

		/*----------  Controlador iniciar sesion  ----------*/
		public function iniciarSesionControlador(){

			$usuario=$this->limpiarCadena($_POST['login_usuario']);
		    $email=$this->limpiarCadena($_POST['login_email']);

		    # Verificando campos obligatorios #
		    if($usuario=="" || $email==""){
		        echo "<script>
			        Swal.fire({
					  icon: 'error',
					  title: 'Ocurrió un error inesperado',
					  text: 'No has llenado todos los campos que son obligatorios'
					});
				</script>";
		    }else{

			    # Verificando integridad de los datos #
			    if($this->verificarDatos("[a-zA-Z0-9]{4,20}",$usuario)){
			        echo "<script>
				        Swal.fire({
						  icon: 'error',
						  title: 'Ocurrió un error inesperado',
						  text: 'El USUARIO no coincide con el formato solicitado'
						});
					</script>";
			    }else{

                    # Verificando email #
		    if($email!=""){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$check_email=$this->ejecutarConsulta("SELECT email FROM usuario WHERE email='$email'");
					if($check_email->rowCount()>0){
						$alerta=[
							"tipo"=>"simple",
							"titulo"=>"Ocurrió un error inesperado",
							"texto"=>"El EMAIL que acaba de ingresar ya se encuentra registrado en el sistema, por favor verifique e intente nuevamente",
							"icono"=>"error"
						];
						return json_encode($alerta);
						exit();
					}
				}else{
					$alerta=[
						"tipo"=>"simple",
						"titulo"=>"Ocurrió un error inesperado",
						"texto"=>"Ha ingresado un correo electrónico no valido",
						"icono"=>"error"
					];
					return json_encode($alerta);
					exit();
				}


					    # Verificando usuario #
					    $check_usuario=$this->ejecutarConsulta("SELECT * FROM usuario WHERE usuario='$usuario'");

					    if($check_usuario->rowCount()==1){

					    	$check_usuario=$check_usuario->fetch();

					    	if($check_usuario['usuario']==$usuario && password_verify($email,$check_usuario['email'])){

					    		$_SESSION['id']=$check_usuario['user_id'];
					            $_SESSION['nombre']=$check_usuario['user_nombre'];
					            $_SESSION['apellido']=$check_usuario['user_apellido'];
                                $_SESSION['email']=$check_usuario['user_email'];
					            $_SESSION['usuario']=$check_usuario['user_usuario'];
                                $_SESSION['telefono']=$check_usuario['user_telefono'];


					            if(headers_sent()){
					                echo "<script> window.location.href='".APP_URL."dashboard/'; </script>";
					            }else{
					                header("Location: ".APP_URL."dashboard/");
					            }

					    	}else{
					    		echo "<script>
							        Swal.fire({
									  icon: 'error',
									  title: 'Ocurrió un error inesperado',
									  text: 'Usuario o email incorrectos'
									});
								</script>";
					    	}

					    }else{
					        echo "<script>
						        Swal.fire({
								  icon: 'error',
								  title: 'Ocurrió un error inesperado',
								  text: 'Usuario o email incorrectos'
								});
							</script>";
					    }
				    }
			    }
		    }
		}


		/*----------  Controlador cerrar sesion  ----------*/
		public function cerrarSesionControlador(){

			session_destroy();

		    if(headers_sent()){
                echo "<script> window.location.href='".APP_URL."login/'; </script>";
            }else{
                header("Location: ".APP_URL."login/");
            }
		}

	}