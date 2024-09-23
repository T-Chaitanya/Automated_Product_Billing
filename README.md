# Automated-Product-Billing

Automated-Product-Billing is an automated billing web application that leverages machine learning to detect products based on their size and type using a camera. It calculates product costs and generates bills for the customer, simplifying the checkout process in retail stores.

## Features
- Automatic product detection using a web camera
- TensorFlow Faster RCNN Inception model for object detection
- Generates bill based on detected product names and sizes
- Web-based interface for easy integration in stores

## Table of Contents
- [Architecture](#architecture)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Training](#training)
- [Running the Application](#running-the-application)
- [Code Explanation](#code-explanation)
- [Contributing](#contributing)
- [License](#license)

## Architecture
The architecture consists of:
1. **Retailer Local Server**: Captures product images via a camera and sends them for processing.
2. **Detection**: Processes the image, runs it through a TensorFlow session using the Faster RCNN Inception model, and identifies product names and sizes.
3. **Response**: Classifies products, calculates quantities and costs, and generates the bill.

## Prerequisites
Before setting up the application, ensure you have the following installed:
- 2 GB Graphics Card (Nvidia)
- 8 GB RAM
- 50 GB Hard Disk
- Web Camera
- Windows 10 OS
- [Anaconda](https://www.anaconda.com/)
- [CUDA](https://developer.nvidia.com/cuda-toolkit)
- [cuDNN](https://developer.nvidia.com/cudnn)
- Python 3.5
- TensorFlow
- LabelImg for labeling images
- TensorFlow Object Detection API

## Installation

### Step 1: Set Up Anaconda Environment
Create a new virtual environment for TensorFlow:
bash
conda create -n tensorflow1 pip python=3.5
conda activate tensorflow1
python -m pip install --upgrade pip


### Step 2: Install Required Packages
bash
pip install --ignore-installed --upgrade tensorflow-gpu
conda install -c anaconda protobuf
pip install pillow lxml Cython contextlib2 jupyter matplotlib pandas opencv-python


### Step 3: Configure PYTHONPATH
Set the PYTHONPATH variable to include necessary directories:
bash
set PYTHONPATH=C:\tensorflow1\models;C:\tensorflow1\models\research;C:\tensorflow1\models\research\slim


### Step 4: Compile Protobuf Files
Navigate to the `models/research` directory and compile the necessary protobufs:
bash
protoc --python_out=. .\object_detection\protos\*.proto


### Step 5: Run Setup Scripts
From the `models/research` directory, run:
bash
python setup.py build
python setup.py install


## Training

### Step 1: Gather and Label Images
Collect 100 images for each product class and use LabelImg to annotate them. Save them in `C:\tensorflow1\models\research\object_detection\images`.

### Step 2: Generate CSV Files from XML Annotations
Run the following script to convert XML labels to CSV:
bash
python xml_to_csv.py

This generates `train_labels.csv` and `test_labels.csv`.

### Step 3: Create TFRecord Files
Generate TFRecord files using the following commands:
bash
python generate_tfrecord.py --csv_input=images/train_labels.csv --image_dir=images/train --output_path=train.record
python generate_tfrecord.py --csv_input=images/test_labels.csv --image_dir=images/test --output_path=test.record


### Step 4: Train the Model
Configure your training pipeline, then train the model using:
bash
python train.py --logtostderr --train_dir=training/ --pipeline_config_path=training/faster_rcnn_inception_v2_pets.config

After training is complete, checkpoints will be created.

### Step 5: Export Inference Graph
Once training is complete, export the inference graph:
bash
python export_inference_graph.py --input_type image_tensor --pipeline_config_path training/faster_rcnn_inception_v2_pets.config --trained_checkpoint_prefix training/model.ckpt-20012 --output_directory inference_graph


## Running the Application

### Step 1: Capture Images
The web application captures images using a webcam and saves them to the `upload/` folder.

### Step 2: Run Object Detection
The web application sends an image processing request to the ML model using the following shell command in `index.php`:
php
$p="activate tensorflow1 & cd C:\\tensorflow1\\models\\research\\object_detection & python Object_detection_image.py C:\\xampp\\htdocs\\webapp\\upload\\"."ImageName.extension";
$out=shell_exec($p);

The `Object_detection_image.py` script returns a tuple with the product names and areas, which is used to generate the bill.

## Code Explanation

### Web Application Workflow
1. The web application captures a product image and saves it.
2. A PHP script triggers the TensorFlow model to process the image and returns the detected product information.
3. The application processes the results and generates the final bill.

## Contributing
Feel free to open issues or submit pull requests. For major changes, please open an issue first to discuss what you would like to change.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
