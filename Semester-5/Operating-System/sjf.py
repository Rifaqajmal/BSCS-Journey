def sjf(processes):
    n = len(processes)
    completed = [False] * n
    current_time = 0
    completed_count = 0

    waiting_time = [0] * n
    turnaround_time = [0] * n

    while completed_count < n:
        idx = -1
        min_bt = float('inf')

        for i in range(n):
            if processes[i][1] <= current_time and not completed[i]:
                if processes[i][2] < min_bt:
                    min_bt = processes[i][2]
                    idx = i

        if idx != -1:
            pid, at, bt = processes[idx]
            current_time += bt

            turnaround_time[idx] = current_time - at
            waiting_time[idx] = turnaround_time[idx] - bt

            completed[idx] = True
            completed_count += 1
        else:
            current_time += 1

    print("PID\tAT\tBT\tWT\tTAT")
    for i in range(n):
        print(f"P{processes[i][0]}\t{processes[i][1]}\t{processes[i][2]}\t{waiting_time[i]}\t{turnaround_time[i]}")


# Example input: [PID, Arrival Time, Burst Time]
processes = [
    [1, 0, 6],
    [2, 1, 8],
    [3, 2, 7],
    [4, 3, 3]
]

sjf(processes)