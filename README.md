# Project 3: Deploying a Containerized LAMP Application to Amazon ECS

## 1. Project Overview
This document provides step-by-step guidance for deploying a containerized LAMP (Linux, Apache, MySQL, PHP) application to Amazon Elastic Container Service (ECS). ECS will handle scaling, management, and orchestration of containers, ensuring high availability, reliability, and flexibility.

## 2. Prerequisites
### 2.1 AWS Account
Ensure you have an AWS account with the necessary permissions to deploy resources in ECS, ECR, RDS, and other AWS services.

### 2.2 Docker Knowledge
Familiarity with Docker is required to build and containerize the LAMP application before pushing it to AWS.

### 2.3 AWS CLI & ECS CLI
Install AWS CLI and ECS CLI to interact with AWS services.
Configure AWS CLI using:
```sh
aws configure
```
Enter your AWS Access Key, Secret Key, Region, and Output Format when prompted.

### 2.4 IAM Permissions
Ensure your IAM role/user has the following permissions:
- Amazon ECS Full Access
- Amazon ECR Full Access
- Amazon RDS Full Access
- CloudWatch Logs Full Access
- CloudFormation Full Access

## 3. Setting Up Amazon Elastic Container Registry (ECR)
### 3.1 Create an ECR Repository
```sh
aws ecr create-repository --repository-name lamp-app
```
This will create an ECR repository named `lamp-app`.

### 3.2 Authenticate Docker to ECR
```sh
aws ecr get-login-password | docker login --username AWS --password-stdin <aws_account_id>.dkr.ecr.<region>.amazonaws.com
```
Replace `<aws_account_id>` and `<region>` with your AWS details.

## 4. Building and Pushing the LAMP Docker Image to ECR
### 4.1 Create a Dockerfile for the LAMP Application
Ensure you have a `Dockerfile` in your project directory.

### 4.2 Build the Docker Image
```sh
docker build -t lamp-app .
```

### 4.3 Tag the Image for ECR
```sh
docker tag lamp-app:latest <aws_account_id>.dkr.ecr.<region>.amazonaws.com/lamp-app:latest
```

### 4.4 Push the Image to ECR
```sh
docker push <aws_account_id>.dkr.ecr.<region>.amazonaws.com/lamp-app:latest
```

## 5. Configuring Amazon ECS
### 5.1 Create an ECS Cluster
```sh
aws ecs create-cluster --cluster-name lamp-cluster
```

### 5.2 Define a Task Definition
Create an ECS task definition JSON file (e.g., `task-definition.json`).

### Register the Task Definition
```sh
aws ecs register-task-definition --cli-input-json file://task-definition.json
```

## 6. Deploying the LAMP Application on ECS
### 6.1 Create an ECS Service
```sh
aws ecs create-service \
    --cluster lamp-cluster \
    --service-name lamp-service \
    --task-definition lamp-task \
    --desired-count 1 \
    --launch-type FARGATE
```
This deploys the LAMP application using Fargate.

## 7. Continuous Deployment with Jenkins
### 7.1 Install Required Plugins
In Jenkins, install the following plugins:
- Pipeline Plugin
- Amazon EC2 Plugin
- AWS Credentials Plugin
- Docker Pipeline Plugin

### 7.2 Configure AWS Credentials in Jenkins
Go to **Manage Jenkins** → **Manage Credentials** → **Global Credentials**.
Add AWS Access Key and Secret Key.

### 7.3 Create a Jenkinsfile
Add the following `Jenkinsfile` to your repository:
```groovy
pipeline {
    agent any
    environment {
        AWS_REGION = 'eu-west-1'
        AWS_ACCOUNT_ID = '122610498016'
        ECR_REPO_NAME = 'lamp-app'
        ECS_CLUSTER_NAME = 'lampapp-cluster'
        ECS_SERVICE_NAME = 'lampapp-service'
        ECS_TASK_FAMILY = 'lampapp-task'
        IMAGE_TAG = "latest"
    }
    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', url: 'https://github.com/Kaydarkey/lampECS.git'
            }
        }
        stage('Login to ECR') {
            steps {
                withAWS(credentials: 'aws-credentials', region: "${AWS_REGION}") {
                    sh """
                    aws ecr get-login-password --region $AWS_REGION | docker login --username AWS --password-stdin ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com
                    """
                }
            }
        }
        stage('Build & Tag Docker Image') {
            steps {
                script {
                    sh """
                    docker build -t ${ECR_REPO_NAME}:${IMAGE_TAG} .
                    docker tag ${ECR_REPO_NAME}:${IMAGE_TAG} ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPO_NAME}:${IMAGE_TAG}
                    """
                }
            }
        }
        stage('Push Image to ECR') {
            steps {
                withAWS(credentials: 'aws-credentials', region: "${AWS_REGION}") {
                    sh """
                    docker push ${AWS_ACCOUNT_ID}.dkr.ecr.${AWS_REGION}.amazonaws.com/${ECR_REPO_NAME}:${IMAGE_TAG}
                    """
                }
            }
        }
        stage('Update ECS Service') {
            steps {
                withAWS(credentials: 'aws-credentials', region: "${AWS_REGION}") {
                    sh """
                    aws ecs update-service --cluster ${ECS_CLUSTER_NAME} --service ${ECS_SERVICE_NAME} --force-new-deployment
                    """
                }
            }
        }
    }
}
```

### 7.4 Run the Pipeline
- In Jenkins, create a new pipeline job.
- Link it to your repository.
- Run the build to deploy the latest changes automatically.

## 8. Testing the Deployment
Retrieve the public IP or DNS name of your ECS task:
```sh
aws ecs list-tasks --cluster lamp-cluster
```
Get task details:
```sh
aws ecs describe-tasks --cluster lamp-cluster --tasks <task_id>
```
Open the public IP in a browser to verify the LAMP application is running.

## 9. Monitoring and Logging
- **ECS Console:** Monitor the application from the AWS ECS Dashboard.
- **CloudWatch Logs:** View logs using:
```sh
aws logs describe-log-groups
```
- **Scaling:** Modify the desired count of containers to handle more traffic:
```sh
aws ecs update-service --cluster lamp-cluster --service lamp-service --desired-count 2
```

## 10. Conclusion
This guide covers deploying a containerized LAMP application to Amazon ECS, integrating it with ECR, RDS, and Jenkins for a scalable and automated deployment. With this setup, your application is highly available, secure, and easy to maintain.

