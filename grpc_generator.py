import os
import subprocess

def generate_grpc_php_files(proto_dir, output_dir):
    if not os.path.exists(output_dir):
        os.makedirs(output_dir)

    proto_files = [f for f in os.listdir(proto_dir) if f.endswith('.proto')]

    for proto_file in proto_files:
        proto_file_path = os.path.join(proto_dir, proto_file)

        command = [
            'protoc',
            f'-I={proto_dir}',
            f'--php_out={output_dir}',
            f'--grpc_out={output_dir}',
            f'--plugin=protoc-gen-grpc=$(which grpc_php_plugin)',
            proto_file_path
        ]

        try:
            subprocess.run(" ".join(command), check=True, shell=True)
            print(f"[✓] Generated PHP gRPC files for: {proto_file}")
        except subprocess.CalledProcessError as e:
            print(f"[✗] Failed to generate gRPC PHP files for {proto_file}: {e}")

if __name__ == "__main__": 
    current_dir = os.path.dirname(os.path.abspath(__file__))   
    proto_directory = os.path.join(current_dir, "proto")
    output_directory = os.path.join(current_dir, "") 

    generate_grpc_php_files(proto_directory, output_directory)
