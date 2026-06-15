from mpi4py import MPI
import time

comm = MPI.COMM_WORLD
rank = comm.Get_rank()

if rank == 0:
    logs = ["Log1", "Log2", "Log3"]
    
    for log in logs:
        req = comm.isend(log, dest=1, tag=0)
        req.wait()
        time.sleep(1)

elif rank == 1:
    for i in range(3):
        req = comm.irecv(source=0, tag=0)
        log = req.wait()
        print("Logger received:", log)