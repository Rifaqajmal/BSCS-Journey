from mpi4py import MPI
import time
import random

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

jobs = None

if rank == 0:
    jobs = [10,20,30,40,50,60]

job = comm.scatter(jobs, root=0)

print("Server", rank, "processing job", job)

time.sleep(random.randint(1,3))

result = job * 2

results = comm.gather(result, root=0)

if rank == 0:
    print("Processed results:", results)