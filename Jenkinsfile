pipeline {
    agent any

    stages {
        stage('git checkout') {
            steps {
                git credentialsId: 'github-crdt', url: 'https://github.com/DJDERNANE/stock-manager'
            }
        }
        stage('Build') {
            steps {
                echo 'Building...'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing...'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deploying...'
            }
        }
    }
}