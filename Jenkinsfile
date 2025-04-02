pipeline {
    agent any

    environment {
        AWS_REGION = 'eu-west-1'  // Change to your AWS region
        AWS_ACCOUNT_ID = '122610498016'
        ECR_REPO_NAME = 'lamp-app'
        ECS_CLUSTER_NAME = 'lamp-cluster'
        ECS_SERVICE_NAME = 'lamp-service'
        ECS_TASK_FAMILY = 'lamp-task'
        IMAGE_TAG = "${env.BUILD_NUMBER}"
    }

    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', url: 'https://github.com/Kaydarkey/lampECS.git'
                sh """
                   aws --version
                """
            }
        }

        stage('Login to Amazon ECR') {
            steps {
                withAWS(credentials: 'aws-credentials', region: "${AWS_REGION}") {
                    sh """
                        aws ecr get-login-password --region $AWS_REGION | docker login --username AWS --password-stdin $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com
                        echo "login successful"
                    """
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    sh """
                        docker build -t $ECR_REPO_NAME:$IMAGE_TAG .
                        docker tag $ECR_REPO_NAME:$IMAGE_TAG $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$ECR_REPO_NAME:$IMAGE_TAG
                    """
                }
            }
        }

        stage('Push Image to ECR') {
            steps {
                withAWS(credentials: 'aws-credentials', region: "${AWS_REGION}") {
                    sh """
                        docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/$ECR_REPO_NAME:$IMAGE_TAG
                    """
                }
            }
        }

        stage('Update ECS Service') {
            steps {
                withAWS(credentials: 'aws-credentials', region: "${AWS_REGION}") {
                    sh """
                        aws ecs update-service --cluster $ECS_CLUSTER_NAME --service $ECS_SERVICE_NAME --force-new-deployment
                    """
                }
            }
        }
    }
}
